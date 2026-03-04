<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index', [
            'user'    => auth()->user(),
            'company' => Company::current(),
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password'     => ['nullable', 'confirmed', Password::defaults()],
        ]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        // Update company info (admin only, when company fields are submitted)
        if ($user->role === 'admin' && $request->has('company_name')) {
            $companyValidated = $request->validate([
                'company_name'               => 'required|string|max:255',
                'company_rut'                => 'nullable|string|max:20',
                'company_address'            => 'nullable|string|max:255',
                'company_phone'              => 'nullable|string|max:30',
                'company_email'              => 'nullable|email|max:255',
                'quotation_validity_days'    => 'required|integer|min:1|max:365',
                'folio_counter'              => 'required|integer|min:1',
            ]);

            Company::current()->update([
                'name'                    => $companyValidated['company_name'],
                'rut'                     => $companyValidated['company_rut'],
                'address'                 => $companyValidated['company_address'],
                'phone'                   => $companyValidated['company_phone'],
                'email'                   => $companyValidated['company_email'],
                'quotation_validity_days' => $companyValidated['quotation_validity_days'],
                'folio_counter'           => $companyValidated['folio_counter'],
            ]);
        }

        return back()->with('success', 'Perfil actualizado exitosamente.');
    }
}
