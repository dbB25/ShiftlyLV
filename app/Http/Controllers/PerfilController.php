<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function actualizar(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'min:2', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
        ]);

        $usuario = Auth::user();

        $usuario->name = $datos['nombre'];
        $usuario->apellido = $datos['apellido'];

        $usuario->save();

        return back()->with(
            'success',
            '¡Tu perfil se actualizó correctamente!'
        );
    }
}