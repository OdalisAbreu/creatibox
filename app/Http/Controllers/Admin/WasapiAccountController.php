<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WasapiAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WasapiAccountController extends Controller
{
    public function edit(): View
    {
        $account = WasapiAccount::first() ?? new WasapiAccount();
        return view('admin.wasapi.edit', compact('account'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'token' => 'required|string|max:500',
            'wasapi_id' => 'required|string|max:100',
            'final_message' => 'nullable|string|max:2000',
        ]);

        $account = WasapiAccount::first();

        if ($account) {
            $account->update($validated);
        } else {
            WasapiAccount::create($validated);
        }

        return redirect()->route('admin.wasapi.edit')->with('success', 'Configuración de Wasapi guardada exitosamente.');
    }
}
