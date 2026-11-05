
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\RecuperarPasswordController;

/*
|--------------------------------------------------------------------------
| Rutas Principales / Sitio Público (html/index)
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
| Rutas de Autenticación / Cuenta (html/login)
|--------------------------------------------------------------------------
*/

Route::get('/perfil', function () {
    return view('html.login.perfil');
})->middleware('auth');

Route::get('/login', function () {
    return view('html.login.login');
});

Route::post('/perfil', [PerfilController::class, 'actualizar'])
    ->middleware('auth')
    ->name('perfil.actualizar');

Route::get('/registro', function () {
    return view('html.login.registro');
});

Route::post('/registro', [RegistroController::class, 'registrar'])
    ->name('registro.registrar');

// Mostrar formulario de recuperación
Route::get('/recuperar-password', function () {
    return view('html.login.recuperar-password');
});

// Procesar solicitud de recuperación
Route::post('/recuperar-password', [RecuperarPasswordController::class, 'enviarEnlace'])
    ->name('password.email');

// Mostrar formulario para establecer una nueva contraseña mediante token
Route::get('/new-password/{token}', [
    RecuperarPasswordController::class,
    'mostrarFormularioRestablecimiento'
])->name('password.reset');

// Procesar y guardar la nueva contraseña
Route::post('/new-password', [
    RecuperarPasswordController::class,
    'restablecerPassword'
])->name('password.update');

// Si se intenta entrar sin token, regresar a recuperación
Route::get('/new-password', function () {
    return redirect('/recuperar-password');
});

Route::get('/verificacion-correo', function () {
    return view('html.login.verificacion-correo');
});

/*
|--------------------------------------------------------------------------
| Rutas del Dashboard / Panel de Usuario (html/dashboard)
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
