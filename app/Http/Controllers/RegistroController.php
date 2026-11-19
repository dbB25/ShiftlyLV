<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistroRequest;
use App\Models\User;

class RegistroController extends Controller
{
    public function registrar(RegistroRequest $request)
    {
        $datos = $request->validated();

        User::create([
            'name' => $datos['nombre'],
            'carrera' => $datos['carrera'],
            'email' => $datos['email'],
            'password' => $datos['password'],
        ]);

        return redirect('/login')->with(
            'success',
            '¡Tu cuenta se creó correctamente!'
        );
    }
}
