<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\RecuperarPasswordController;

/*
|--------------------------------------------------------------------------
| Sitio Público (html/index)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('html.index.index');
});

Route::get('/contacto', function () {
    return view('html.index.contacto');
});

Route::get('/servicios', function () {
    return view('html.index.servicios');
});

/*
|--------------------------------------------------------------------------
| Autenticación (html/login)
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('html.login.login');
})->name('login');

// Redirige al inicio (index.blade.php) al iniciar sesión
Route::post('/login', function () {
    return redirect('/');
})->name('login.autenticar');

Route::get('/registro', function () {
    return view('html.login.registro');
});

// Redirige al login al presionar 'Crear Cuenta' (evita error de BD temporalmente)
Route::post('/registro', function () {
    return redirect('/login');
})->name('registro.registrar');

Route::get('/perfil', function () {
    return view('html.login.perfil');
})->middleware('auth');

Route::post('/perfil', [PerfilController::class, 'actualizar'])
    ->middleware('auth')
    ->name('perfil.actualizar');

// Recuperación de contraseña
Route::get('/recuperar-password', function () {
    return view('html.login.recuperar-password');
});

Route::post('/recuperar-password', [RecuperarPasswordController::class, 'enviarEnlace'])
    ->name('password.email');

// Restablecimiento de contraseña
Route::get('/new-password/{token}', [
    RecuperarPasswordController::class,
    'mostrarFormularioRestablecimiento'
])->name('password.reset');

Route::post('/new-password', [
    RecuperarPasswordController::class,
    'restablecerPassword'
])->name('password.update');

Route::get('/new-password', function () {
    return redirect('/recuperar-password');
});

Route::get('/verificacion-correo', function () {
    return view('html.login.verificacion-correo');
});

/*
|--------------------------------------------------------------------------
| Panel / Dashboard (html/dashboard)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('html.dashboard.dashboard');
});

Route::get('/diagnostico', function () {
    return view('html.dashboard.diagnostico');
});

Route::get('/ejercicios', function () {
    return view('html.dashboard.Ejercicios');
});

Route::get('/glosario', function () {
    return view('html.dashboard.glosario');
});

Route::get('/recomendaciones', function () {
    return view('html.dashboard.recomendaciones');
});

Route::get('/resultado', function () {
    return view('html.dashboard.resultado');
});

Route::get('/resultados-leccion', function () {
    return view('html.dashboard.resultados-leccion');
});

Route::get('/ruta-aprendizaje', function () {
    return view('html.dashboard.rutaDeAprendizaje');
});