from types import SimpleNamespace
from unittest.mock import patch

from django.test import SimpleTestCase
from django.urls import reverse

from .views import SESSION_EMPLEADO_ID


class LoginViewTests(SimpleTestCase):
    @patch("ventas.views.Empleado.objects.filter")
    def test_valid_login_starts_session(self, filter_employees):
        filter_employees.return_value.first.return_value = SimpleNamespace(pk=7)

        response = self.client.post(
            reverse("login"),
            {"usuario": "emp01", "contrasena": "123"},
        )

        self.assertEqual(response.status_code, 302)
        self.assertEqual(response["Location"], reverse("inicio"))
        self.assertEqual(self.client.session[SESSION_EMPLEADO_ID], 7)
        filter_employees.assert_any_call(usuario="emp01", dni="123")

    @patch("ventas.views.Empleado.objects.filter")
    def test_invalid_login_shows_error(self, filter_employees):
        filter_employees.return_value.first.return_value = None

        response = self.client.post(
            reverse("login"),
            {"usuario": "emp01", "contrasena": "wrong"},
        )

        self.assertEqual(response.status_code, 200)
        self.assertContains(response, "Usuario o contraseña incorrectos.")

    def test_employee_page_requires_login(self):
        response = self.client.get(reverse("empleados"))

        self.assertRedirects(response, reverse("login"))

    @patch("ventas.views.Empleado.objects.all")
    @patch("ventas.views.Empleado.objects.filter")
    def test_employee_page_renders_for_logged_in_user(self, filter_employees, all_employees):
        employee = SimpleNamespace(
            pk=7,
            id=7,
            dni="123",
            nombres="Pedro Hernandez",
            telefono="988252459",
            estado="1",
            usuario="emp01",
        )
        filter_employees.return_value.first.return_value = employee
        all_employees.return_value = [employee]
        self.client.post(
            reverse("login"),
            {"usuario": "emp01", "contrasena": "123"},
        )

        response = self.client.get(reverse("empleados"))

        self.assertEqual(response.status_code, 200)
        self.assertContains(response, "Pedro Hernandez")
        self.assertContains(response, "ACCIONES")
