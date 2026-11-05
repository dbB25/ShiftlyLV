<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactoController extends Controller
{
    public function index()
    {
        return view('contacto');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'  => 'required|string|max:255',
            'email'   => 'required|email',
            'mensaje' => 'required|string',
        ]);

        return back()->with('success', '¡Mensaje enviado con éxito!');
    }
}