from django.contrib import messages
from django.http import HttpResponseNotAllowed
from django.shortcuts import get_object_or_404, redirect, render
from django.views.decorators.http import require_POST

from .forms import EmpleadoForm, LoginForm
from .models import Empleado


SESSION_EMPLEADO_ID = "empleado_id"


def _empleado_actual(request):
    empleado_id = request.session.get(SESSION_EMPLEADO_ID)
    if empleado_id is None:
        return None
    return Empleado.objects.filter(pk=empleado_id).first()


def _requiere_sesion(request):
    empleado = _empleado_actual(request)
    if empleado is None:
        request.session.flush()
        return None
    return empleado


def login_view(request):
    if request.method == "POST":
        form = LoginForm(request.POST)
        if form.is_valid():
            empleado = Empleado.objects.filter(
                usuario=form.cleaned_data["usuario"],
                dni=form.cleaned_data["contrasena"],
            ).first()
            if empleado is not None:
                request.session.cycle_key()
                request.session[SESSION_EMPLEADO_ID] = empleado.pk
                return redirect("inicio")
            form.add_error(None, "Usuario o contraseña incorrectos.")
    elif request.method == "GET":
        if _requiere_sesion(request) is not None:
            return redirect("inicio")
        form = LoginForm()
    else:
        return HttpResponseNotAllowed(["GET", "POST"])

    return render(request, "ventas/login.html", {"form": form})


def inicio(request):
    empleado = _requiere_sesion(request)
    if empleado is None:
        return redirect("login")
    return render(request, "ventas/inicio.html", {"empleado": empleado})


@require_POST
def salir(request):
    request.session.flush()
    return redirect("login")


def empleados(request):
    if _requiere_sesion(request) is None:
        return redirect("login")
    empleado_id = request.GET.get("editar")
    empleado = None
    if empleado_id:
        empleado = get_object_or_404(Empleado, pk=empleado_id)
    form = EmpleadoForm(instance=empleado)
    return render(
        request,
        "ventas/empleados.html",
        {
            "form": form,
            "empleados": Empleado.objects.all(),
            "empleado_en_edicion": empleado,
        },
    )


@require_POST
def agregar_empleado(request):
    if _requiere_sesion(request) is None:
        return redirect("login")
    form = EmpleadoForm(request.POST)
    if form.is_valid():
        form.save()
        messages.success(request, "Empleado agregado correctamente.")
        return redirect("empleados")
    return render(
        request,
        "ventas/empleados.html",
        {"form": form, "empleados": Empleado.objects.all()},
    )


@require_POST
def editar_empleado(request, empleado_id):
    if _requiere_sesion(request) is None:
        return redirect("login")
    empleado = get_object_or_404(Empleado, pk=empleado_id)
    form = EmpleadoForm(request.POST, instance=empleado)
    if form.is_valid():
        form.save()
        messages.success(request, "Empleado actualizado correctamente.")
        return redirect("empleados")
    return render(
        request,
        "ventas/empleados.html",
        {
            "form": form,
            "empleados": Empleado.objects.all(),
            "empleado_en_edicion": empleado,
        },
    )


@require_POST
def eliminar_empleado(request, empleado_id):
    if _requiere_sesion(request) is None:
        return redirect("login")
    empleado = get_object_or_404(Empleado, pk=empleado_id)
    empleado.delete()
    messages.success(request, "Empleado eliminado correctamente.")
    return redirect("empleados")


def modulo_vacio(request, titulo):
    if _requiere_sesion(request) is None:
        return redirect("login")
    return render(request, "ventas/modulo_vacio.html", {"titulo": titulo})
