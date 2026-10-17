@extends('layouts.app')

@section('title', 'Empleado')

@section('content')
    <div class="employee-page">
        <section class="card employee-form-card">
            <div class="card-body">
                <h1 class="section-title">{{ $empleado->exists ? 'Editar empleado' : 'Empleado' }}</h1>
                @if (session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif
                @error('employee')
                    <div class="alert alert-danger" role="alert">{{ $message }}</div>
                @enderror
                @if ($errors->any() && !$errors->has('employee'))
                    <div class="alert alert-danger" role="alert">Revise los campos e intente nuevamente.</div>
                @endif
                <form action="{{ $empleado->exists ? route('empleados.update', $empleado->IdEmpleado) : route('empleados.store') }}"
                      method="POST">
                    @csrf
                    @if ($empleado->exists)
                        @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="Dni">Dni</label>
                        <input id="Dni" name="Dni" class="form-control" type="text" maxlength="8" required
                               value="{{ old('Dni', $empleado->Dni) }}">
                        @error('Dni')<small class="field-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="form-group">
                        <label for="Nombres">Nombres</label>
                        <input id="Nombres" name="Nombres" class="form-control" type="text" maxlength="255"
                               value="{{ old('Nombres', $empleado->Nombres) }}">
                        @error('Nombres')<small class="field-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="form-group">
                        <label for="Telefono">Telefono</label>
                        <input id="Telefono" name="Telefono" class="form-control" type="text" maxlength="9"
                               value="{{ old('Telefono', $empleado->Telefono) }}">
                        @error('Telefono')<small class="field-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="form-group">
                        <label for="Estado">Estado</label>
                        <input id="Estado" name="Estado" class="form-control" type="text" maxlength="1"
                               value="{{ old('Estado', $empleado->Estado) }}">
                        @error('Estado')<small class="field-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="form-group">
                        <label for="User">Usuario</label>
                        <input id="User" name="User" class="form-control" type="text" maxlength="8"
                               value="{{ old('User', $empleado->User) }}">
                        @error('User')<small class="field-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="form-actions">
                        <button class="btn btn-info" type="submit"> {{ $empleado->exists ? 'Actualizar' : 'Agregar' }}</button>
                        @if ($empleado->exists)
                            <a class="btn btn-secondary" href="{{ route('empleados.index') }}">Cancelar</a>
                        @endif
                    </div>
                </form>
            </div>
        </section>
        <section class="employee-table">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>DNI</th>
                            <th>NOMBRES</th>
                            <th>TELEFONO</th>
                            <th>ESTADO</th>
                            <th>USUARIO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($empleados as $item)
                            <tr>
                                <td>{{ $item->IdEmpleado }}</td>
                                <td>{{ $item->Dni }}</td>
                                <td>{{ $item->Nombres }}</td>
                                <td>{{ $item->Telefono }}</td>
                                <td>{{ $item->Estado }}</td>
                                <td>{{ $item->User }}</td>
                                <td class="actions-cell">
                                    <a class="btn btn-warning btn-sm" href="{{ route('empleados.edit', $item->IdEmpleado) }}">Editar</a>
                                    <form action="{{ route('empleados.destroy', $item->IdEmpleado) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar este empleado?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No hay empleados registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
