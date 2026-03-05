<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        $roles = Role::orderBy('label')->get();
        return view('users.index', compact('users', 'roles'));
    }

    public function permissions()
    {
        $sections = [
            'Panel General' => [
                'Ver dashboard'     => ['admin', 'recepcion', 'taller'],
            ],
            'Presupuestos' => [
                'Ver listado'       => ['admin', 'recepcion', 'taller'],
                'Ver detalle'       => ['admin', 'recepcion', 'taller'],
                'Crear / Editar'    => ['admin', 'recepcion'],
                'Cambiar estado'    => ['admin', 'recepcion'],
                'Descargar PDF'     => ['admin', 'recepcion', 'taller'],
                'Eliminar'          => ['admin'],
            ],
            'Clientes' => [
                'Ver listado'       => ['admin', 'recepcion', 'taller'],
                'Ver detalle'       => ['admin', 'recepcion', 'taller'],
                'Crear / Editar'    => ['admin', 'recepcion'],
                'Eliminar'          => ['admin'],
            ],
            'Vehículos' => [
                'Ver listado'       => ['admin', 'recepcion', 'taller'],
                'Ver detalle'       => ['admin', 'recepcion', 'taller'],
                'Crear / Editar'    => ['admin', 'recepcion'],
                'Eliminar'          => ['admin'],
            ],
            'Liquidadores y Compañías' => [
                'Ver / Crear / Editar' => ['admin', 'recepcion'],
                'Eliminar'          => ['admin'],
            ],
            'Reportes' => [
                'Ver reportes'      => ['admin', 'recepcion'],
                'Descargar PDF'     => ['admin', 'recepcion'],
            ],
            'Administración' => [
                'Gestión de usuarios'       => ['admin'],
                'Catálogo de servicios'     => ['admin'],
                'Marcas y modelos'          => ['admin'],
                'Ver permisos por rol'      => ['admin'],
            ],
        ];

        return view('users.permissions', compact('sections'));
    }

    public function create()
    {
        $branches = Branch::where('active', true)->orderBy('name')->get();
        $roles    = Role::orderBy('label')->get();
        return view('users.create', compact('branches', 'roles'));
    }

    public function store(Request $request)
    {
        $validRoles = Role::pluck('name')->toArray();

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'role'      => ['required', Rule::in($validRoles)],
            'branch_id' => 'nullable|exists:branches,id',
            'password'  => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'role'      => $validated['role'],
            'branch_id' => $validated['branch_id'] ?? null,
            'password'  => Hash::make($validated['password']),
            'active'    => true,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $user)
    {
        $branches = Branch::where('active', true)->orderBy('name')->get();
        $roles    = Role::orderBy('label')->get();
        return view('users.edit', compact('user', 'branches', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validRoles = Role::pluck('name')->toArray();

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'         => ['required', Rule::in($validRoles)],
            'branch_id'    => 'nullable|exists:branches,id',
            'new_password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        // Prevent removing the last admin
        if ($user->role === 'admin' && $validated['role'] !== 'admin') {
            $adminCount = User::where('role', 'admin')->where('active', true)->count();
            if ($adminCount <= 1) {
                return back()->withErrors(['role' => 'No se puede cambiar el rol del único administrador activo.']);
            }
        }

        $user->name      = $validated['name'];
        $user->email     = $validated['email'];
        $user->role      = $validated['role'];
        $user->branch_id = $validated['branch_id'] ?? null;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function toggleActive(User $user)
    {
        if ($user->role === 'admin' && $user->active) {
            $activeAdmins = User::where('role', 'admin')->where('active', true)->count();
            if ($activeAdmins <= 1) {
                return back()->withErrors(['active' => 'No se puede desactivar el único administrador activo.']);
            }
        }

        $user->active = !$user->active;
        $user->save();

        $msg = $user->active ? 'Usuario activado.' : 'Usuario desactivado.';
        return back()->with('success', $msg);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['delete' => 'No puedes eliminar tu propia cuenta.']);
        }

        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->withErrors(['delete' => 'No se puede eliminar el único administrador.']);
            }
        }

        $user->delete();
        return back()->with('success', 'Usuario eliminado.');
    }
}
