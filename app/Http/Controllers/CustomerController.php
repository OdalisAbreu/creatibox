<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Interest;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function create()
    {
        $interests = Interest::orderBy('name')->get();
        return view('customers.form', compact('interests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'regex:/^[0-9]+$/', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'date_of_birth' => ['required', 'date'],
            'age_range' => ['nullable', 'string', 'in:18-24,25-34,35-44,45-54,55-64,65+'],
            'gender' => ['required', 'in:F,M'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'tiktok' => ['nullable', 'string', 'max:100'],
            'occupation' => ['required', 'string', 'max:255'],
            'occupation_other' => ['required_if:occupation,Otro', 'nullable', 'string', 'max:255'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['integer', 'exists:interests,id'],
        ], [
            'phone.regex' => 'El teléfono debe contener solo números, sin espacios.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'occupation_other.required_if' => 'Debe especificar la ocupación cuando selecciona Otro.',
        ]);

        $validated['country'] = $request->input('country', '');
        $validated['instagram'] = $request->input('instagram', '');
        $validated['tiktok'] = $request->input('tiktok', '');
        $validated['age_range'] = $request->input('age_range');

        $interestIds = array_map('intval', $validated['interests'] ?? []);
        unset($validated['interests']);

        $customer = Customer::create($validated);
        $customer->interests()->sync($interestIds);

        return redirect()->route('customers.form')->with('success', 'Registro guardado correctamente.');
    }
}
