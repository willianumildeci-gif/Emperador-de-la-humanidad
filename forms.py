from django import forms

from .models import Empleado


class LoginForm(forms.Form):
    usuario = forms.CharField(max_length=8, label="Usuario")
    contrasena = forms.CharField(
        max_length=8,
        label="Contraseña",
        strip=False,
        widget=forms.PasswordInput,
    )


class EmpleadoForm(forms.ModelForm):
    class Meta:
        model = Empleado
        fields = ("dni", "nombres", "telefono", "estado", "usuario")
        labels = {
            "dni": "DNI",
            "nombres": "Nombres",
            "telefono": "Teléfono",
            "estado": "Estado",
            "usuario": "Usuario",
        }
        widgets = {
            "dni": forms.TextInput(attrs={"class": "form-control"}),
            "nombres": forms.TextInput(attrs={"class": "form-control"}),
            "telefono": forms.TextInput(attrs={"class": "form-control"}),
            "estado": forms.TextInput(attrs={"class": "form-control"}),
            "usuario": forms.TextInput(attrs={"class": "form-control"}),
        }

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        for field in self.fields.values():
            field.widget.attrs.setdefault("class", "form-control")
