<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmpleadoController extends Controller
{
    public function index(): View
    {
        return view('empleados.index', [
            'empleados' => Empleado::query()->orderBy('IdEmpleado')->get(),
            'empleado' => new Empleado,
        ]);
    }

    public function edit(int $id): View
    {
        return view('empleados.index', [
            'empleados' => Empleado::query()->orderBy('IdEmpleado')->get(),
            'empleado' => Empleado::query()->findOrFail($id),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Empleado::query()->create($this->validatedData($request));

        return redirect()->route('empleados.index')->with('success', 'Empleado agregado correctamente.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $empleado = Empleado::query()->findOrFail($id);
        $empleado->update($this->validatedData($request));

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $empleado = Empleado::query()->findOrFail($id);

        try {
            $empleado->delete();
        } catch (QueryException $exception) {
            report($exception);

            return back()->withErrors([
                'employee' => 'No se puede eliminar el empleado porque tiene ventas asociadas.',
            ]);
        }

        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }

    /**
     * @return array{Dni: string, Nombres: ?string, Telefono: ?string, Estado: ?string, User: ?string}
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'Dni' => ['required', 'string', 'max:8'],
            'Nombres' => ['nullable', 'string', 'max:255'],
            'Telefono' => ['nullable', 'string', 'max:9'],
            'Estado' => ['nullable', 'string', 'max:1'],
            'User' => ['nullable', 'string', 'max:8'],
        ]);
    }
}
