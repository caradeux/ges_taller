<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderByRaw("CASE name WHEN 'admin' THEN 1 WHEN 'recepcion' THEN 2 WHEN 'taller' THEN 3 ELSE 4 END")
            ->orderBy('label')
            ->get();

        $roles->each(function ($role) {
            $role->users_count  = User::where('role', $role->name)->count();
            $role->active_count = User::where('role', $role->name)->where('active', true)->count();
        });

        $permissionSummary = [
            'admin' => [
                'Panel General'        => true,
                'Cotizaciones'         => 'Total',
                'Clientes / Vehículos' => 'Total',
                'Reportes'             => true,
                'Seguimiento'          => true,
                'Administración'       => true,
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

        $permissionGroups = config('permission_groups', []);

        return view('roles.index', compact('roles', 'permissionSummary', 'permissionGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label'         => 'required|string|max:100',
            'description'   => 'nullable|string|max:500',
            'badge_color'   => 'required|string|max:30',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $base = Str::slug($validated['label'], '_');
        $name = $base;
        $i = 1;
        while (Role::where('name', $name)->exists()) {
            $name = $base . '_' . $i++;
        }

        Role::create([
            'name'        => $name,
            'label'       => $validated['label'],
            'description' => $validated['description'] ?? null,
            'badge_color' => $validated['badge_color'],
            'permissions' => $validated['permissions'] ?? [],
            'is_system'   => false,
        ]);

        return back()->with('success', "Rol \"{$validated['label']}\" creado correctamente.");
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'label'         => 'required|string|max:100',
            'description'   => 'nullable|string|max:500',
            'badge_color'   => 'required|string|max:30',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $data = [
            'label'       => $validated['label'],
            'description' => $validated['description'] ?? null,
            'badge_color' => $validated['badge_color'],
        ];

        if (!$role->is_system) {
            $data['permissions'] = $validated['permissions'] ?? [];
        }

        $role->update($data);

        return back()->with('success', "Rol \"{$role->label}\" actualizado correctamente.");
    }

    public function destroy(Role $role)
    {
        if ($role->is_system) {
            return back()->with('error', 'Los roles del sistema no pueden eliminarse.');
        }

        if (User::where('role', $role->name)->exists()) {
            return back()->with('error', "No se puede eliminar el rol \"{$role->label}\" porque tiene usuarios asignados.");
        }

        $label = $role->label;
        $role->delete();

        return back()->with('success', "Rol \"{$label}\" eliminado correctamente.");
    }
}
