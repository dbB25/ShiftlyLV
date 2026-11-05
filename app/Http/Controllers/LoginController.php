<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function iniciarSesion(Request $request)
    {
        // Validar los datos
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Comprobar las credenciales
        if (Auth::attempt($credenciales)) {

            $request->session()->regenerate();

            return redirect('/perfil')->with(
                'success',
                '¡Bienvenido de nuevo!'
            );
        }

        // Si los datos son incorrectos
        return back()->withErrors([
            'email' => 'El correo o la contraseña son incorrectos.',
        ])->onlyInput('email');
    }

    public function cerrarSesion(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}