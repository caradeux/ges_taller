<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderByRaw("CASE name WHEN 'admin' THEN 1 WHEN 'recepcion' THEN 2 WHEN 'taller' THEN 3 ELSE 4 END")->get();

        // Attach user count to each role
        $roles->each(function ($role) {
            $role->users_count = User::where('role', $role->name)->count();
            $role->active_count = User::where('role', $role->name)->where('active', true)->count();
        });

        // Permissions summary per role
        $permissionSummary = [
            'admin' => [
                'Panel General'       => true,
                'Cotizaciones'        => 'Total',
                'Clientes / Vehículos'=> 'Total',
                'Reportes'            => true,
                'Seguimiento'         => true,
                'Administración'      => true,
            ],
            'recepcion' => [
                'Panel General'        => true,
                'Cotizaciones'         => 'Crear / Editar / Estado',
                'Clientes / Vehículos' => 'Crear / Editar',
                'Reportes'             => true,
                'Seguimiento'          => true,
                'Administración'       => false,
            ],
            'taller' => [
                'Panel General'        => true,
                'Cotizaciones'         => 'Solo lectura',
                'Clientes / Vehículos' => 'Solo lectura',
                'Reportes'             => false,
                'Seguimiento'          => false,
                'Administración'       => false,
            ],
        ];

        return view('roles.index', compact('roles', 'permissionSummary'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'label'       => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'badge_color' => 'required|string|max:30',
        ]);

        $role->update($validated);

        return back()->with('success', "Rol \"{$role->label}\" actualizado correctamente.");
    }
}
