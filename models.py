from django.db import models


class Empleado(models.Model):
    id = models.AutoField(primary_key=True, db_column="IdEmpleado")
    dni = models.CharField(max_length=8, db_column="Dni")
    nombres = models.CharField(max_length=255, null=True, blank=True, db_column="Nombres")
    telefono = models.CharField(max_length=9, null=True, blank=True, db_column="Telefono")
    estado = models.CharField(max_length=1, null=True, blank=True, db_column="Estado")
    usuario = models.CharField(max_length=8, null=True, blank=True, db_column="User")

    class Meta:
        managed = False
        db_table = "empleado"
        ordering = ("id",)

    def __str__(self):
        return self.nombres or self.usuario or f"Empleado {self.id}"
