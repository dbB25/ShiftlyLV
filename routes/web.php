<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. Sitio Público (html/index)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('html.index.index');
})->name('index');

// Carga directa de la vista contacto.blade.php (sin controlador)
Route::get('/contacto', function () {
    return view('html.index.contacto');
})->name('contacto');

Route::post('/contacto', function () {
    return redirect('/contacto')->with('exito', 'Mensaje enviado correctamente');
})->name('contacto.enviar');

Route::get('/servicios', function () {
    return view('html.index.servicios');
})->name('servicios');


/*
|--------------------------------------------------------------------------
| 2. Autenticación y Cuenta (html/login)
|--------------------------------------------------------------------------
*/

// Inicio de Sesión
Route::get('/login', function () {
    return view('html.login.login');
})->name('login');

Route::post('/login', function () {
    return redirect('/dashboard');
})->name('login.autenticar');

// Registro de Usuario
Route::get('/registro', function () {
    return view('html.login.registro');
})->name('registro');

Route::post('/registro', function () {
    return redirect('/login?registro=exitoso');
})->name('registro.registrar');

// Recuperación de Contraseña
Route::get('/recuperar-password', function () {
    return view('html.login.recuperar-password');
})->name('recuperar-password');

Route::post('/recuperar-password', function () {
    return redirect('/login');
})->name('password.email');

// Restablecimiento de Contraseña
Route::get('/new-password/{token?}', function () {
    return view('html.login.new-password');
})->name('password.reset');

Route::post('/new-password', function () {
    return redirect('/login');
})->name('password.update');

// Verificación de Correo Electrónico
Route::get('/verificacion-correo', function () {
    return view('html.login.verificacion-correo');
})->name('verificacion-correo');

// Perfil de Usuario (Simulado en cliente / Sin BD)
Route::get('/perfil', function () {
    return view('html.login.perfil');
})->name('perfil');

Route::post('/perfil', function () {
    return redirect('/dashboard');
})->name('perfil.actualizar');


/*
|--------------------------------------------------------------------------
| 3. Módulos del Panel / Dashboard (html/dashboard)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('html.dashboard.dashboard');
})->name('dashboard');

Route::get('/ruta-aprendizaje', function () {
    return view('html.dashboard.rutaDeAprendizaje');
})->name('ruta-aprendizaje');

Route::get('/diagnostico', function () {
    return view('html.dashboard.diagnostico');
})->name('diagnostico');

Route::get('/ejercicios', function () {
    return view('html.dashboard.Ejercicios');
})->name('ejercicios');

Route::get('/glosario', function () {
    return view('html.dashboard.glosario');
})->name('glosario');

Route::get('/recomendaciones', function () {
    return view('html.dashboard.recomendaciones');
})->name('recomendaciones');

Route::get('/resultado', function () {
    return view('html.dashboard.resultado');
})->name('resultado');

Route::get('/resultados-leccion', function () {
    return view('html.dashboard.resultados-leccion');
})->name('resultados-leccion');