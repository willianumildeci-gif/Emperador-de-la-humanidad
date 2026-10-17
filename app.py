import logging
import os
import secrets
from functools import wraps

import pymysql
from flask import (
    Flask,
    abort,
    flash,
    redirect,
    render_template,
    request,
    session,
    url_for,
)


app = Flask(__name__)
app.secret_key = os.environ.get("SECRET_KEY") or secrets.token_hex(32)
app.config.update(
    SESSION_COOKIE_HTTPONLY=True,
    SESSION_COOKIE_SAMESITE="Lax",
)

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


def get_db_connection():
    return pymysql.connect(
        host=os.environ.get("DB_HOST", "localhost"),
        port=int(os.environ.get("DB_PORT", "3306")),
        user=os.environ.get("DB_USER", "root"),
        password=os.environ.get("DB_PASSWORD", ""),
        database=os.environ.get("DB_NAME", "bd_ventas"),
        charset="utf8mb4",
        cursorclass=pymysql.cursors.DictCursor,
        connect_timeout=5,
    )


def csrf_token():
    if "_csrf_token" not in session:
        session["_csrf_token"] = secrets.token_hex(32)
    return session["_csrf_token"]


app.jinja_env.globals["csrf_token"] = csrf_token


@app.before_request
def protect_post_requests():
    if request.method == "POST":
        submitted_token = request.form.get("_csrf_token", "")
        expected_token = session.get("_csrf_token", "")
        if not expected_token or not secrets.compare_digest(
            submitted_token, expected_token
        ):
            abort(400, description="Token de formulario no válido.")


def login_required(view):
    @wraps(view)
    def wrapped_view(*args, **kwargs):
        if "employee" not in session:
            return redirect(url_for("index"))
        return view(*args, **kwargs)

    return wrapped_view


@app.get("/")
def index():
    if "employee" in session:
        return redirect(url_for("dashboard"))
    return render_template("login.html")


@app.post("/login")
def login():
    username = request.form.get("txtuser", "").strip()
    password = request.form.get("txtpass", "")
    connection = None
    try:
        connection = get_db_connection()
        with connection.cursor() as cursor:
            cursor.execute(
                """
                SELECT IdEmpleado, Dni, Nombres, Telefono, Estado, `User`
                FROM empleado
                WHERE `User` = %s AND Dni = %s
                """,
                (username, password),
            )
            employee = cursor.fetchone()
    except pymysql.MySQLError:
        logger.exception("No se pudo validar el acceso contra MySQL.")
        flash("No se pudo conectar con la base de datos. Revisa la configuración.", "danger")
        return render_template("login.html"), 503
    finally:
        if connection is not None:
            connection.close()

    if employee is None:
        flash("Usuario o contraseña incorrectos.", "danger")
        return render_template("login.html"), 401

    session.clear()
    session["employee"] = employee
    csrf_token()
    return redirect(url_for("dashboard"))


@app.post("/logout")
def logout():
    session.clear()
    return redirect(url_for("index"))


@app.get("/dashboard")
@login_required
def dashboard():
    return render_template("dashboard.html", employee=session["employee"])


@app.route("/empleados", methods=["GET", "POST"])
@login_required
def employees():
    if request.method == "POST":
        return update_employees()

    connection = None
    selected_employee = None
    try:
        connection = get_db_connection()
        with connection.cursor() as cursor:
            cursor.execute("SELECT * FROM empleado ORDER BY IdEmpleado")
            employee_list = cursor.fetchall()

            selected_id = request.args.get("editar")
            if selected_id:
                cursor.execute(
                    "SELECT * FROM empleado WHERE IdEmpleado = %s",
                    (selected_id,),
                )
                selected_employee = cursor.fetchone()
                if selected_employee is None:
                    flash("No se encontró el empleado solicitado.", "warning")
    except pymysql.MySQLError:
        logger.exception("No se pudo consultar la tabla empleado.")
        flash("No se pudieron cargar los empleados. Revisa la conexión MySQL.", "danger")
        employee_list = []
    finally:
        if connection is not None:
            connection.close()

    return render_template(
        "empleados.html",
        employees=employee_list,
        selected_employee=selected_employee,
    )


def update_employees():
    action = request.form.get("accion")
    employee_id = request.form.get("id", "").strip()
    connection = None

    try:
        connection = get_db_connection()
        with connection.cursor() as cursor:
            if action == "Agregar":
                values = employee_form_values()
                cursor.execute(
                    """
                    INSERT INTO empleado (Dni, Nombres, Telefono, Estado, `User`)
                    VALUES (%s, %s, %s, %s, %s)
                    """,
                    values,
                )
                success_message = "Empleado agregado correctamente."
            elif action == "Actualizar":
                values = employee_form_values()
                if not employee_id.isdigit():
                    abort(400, description="Identificador de empleado no válido.")
                cursor.execute(
                    """
                    UPDATE empleado
                    SET Dni = %s, Nombres = %s, Telefono = %s, Estado = %s, `User` = %s
                    WHERE IdEmpleado = %s
                    """,
                    values + (int(employee_id),),
                )
                success_message = "Empleado actualizado correctamente."
            elif action == "Delete":
                if not employee_id.isdigit():
                    abort(400, description="Identificador de empleado no válido.")
                cursor.execute(
                    "DELETE FROM empleado WHERE IdEmpleado = %s",
                    (int(employee_id),),
                )
                success_message = "Empleado eliminado correctamente."
            else:
                abort(400, description="Acción de empleado no válida.")
        connection.commit()
        flash(success_message, "success")
    except pymysql.MySQLError:
        if connection is not None:
            connection.rollback()
        logger.exception("No se pudo completar la operación de empleado.")
        flash(
            "No se pudo guardar el cambio. Verifica los datos y las relaciones de la base.",
            "danger",
        )
    finally:
        if connection is not None:
            connection.close()

    return redirect(url_for("employees"))


def employee_form_values():
    dni = request.form.get("txtDni", "").strip()
    name = request.form.get("txtNombres", "").strip()
    phone = request.form.get("txtTel", "").strip()
    state = request.form.get("txtEstado", "").strip()
    username = request.form.get("txtUser", "").strip()

    if not dni or len(dni) > 8:
        abort(400, description="El DNI es obligatorio y debe tener máximo 8 caracteres.")
    if len(name) > 255 or len(phone) > 9 or len(state) > 1 or len(username) > 8:
        abort(400, description="Uno o más campos exceden el tamaño permitido.")

    return dni, name, phone, state, username


@app.get("/modulo/<module>")
@login_required
def module_placeholder(module):
    titles = {
        "clientes": "Clientes",
        "productos": "Producto",
        "ventas": "Registrar Venta",
    }
    title = titles.get(module)
    if title is None:
        abort(404)
    return render_template("placeholder.html", title=title)


if __name__ == "__main__":
    app.run(debug=os.environ.get("FLASK_DEBUG") == "1")
