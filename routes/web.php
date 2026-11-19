<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\RecuperarPasswordController;
use App\Http\Controllers\EjercicioController;
use App\Http\Controllers\ContactoController;

/*
|--------------------------------------------------------------------------
| 1. PAGINAS PUBLICAS
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('html.index.index');
})->name('index');

Route::get('/contacto', [ContactoController::class, 'index'])
    ->name('contacto');

Route::post('/contacto', [ContactoController::class, 'enviar'])
    ->name('contacto.enviar');

Route::get('/servicios', function () {
    return view('html.index.servicios');
})->name('servicios');

/*
|--------------------------------------------------------------------------
| 2. AUTENTICACION Y REGISTRO
|--------------------------------------------------------------------------
*/

// Inicio de sesion
Route::get('/login', function () {
    return view('html.login.login');
})->name('login');

Route::post('/login', [LoginController::class, 'iniciarSesion'])
    ->name('login.autenticar');

// Registro
Route::get('/registro', function () {
    return view('html.login.registro');
})->name('registro');

Route::post('/registro', [RegistroController::class, 'registrar'])
    ->name('registro.registrar');

// Recuperacion de contraseña
Route::get('/recuperar-password', function () {
    return view('html.login.recuperar-password');
})->name('recuperar-password');

Route::post('/recuperar-password', [
    RecuperarPasswordController::class,
    'enviarEnlace'
])->name('password.email');

// Restablecimiento de contraseña
Route::get('/new-password/{token}', [
    RecuperarPasswordController::class,
    'mostrarFormularioRestablecimiento'
])->name('password.reset');

Route::get('/new-password', function () {
    return redirect('/recuperar-password');
});

Route::post('/new-password', [
    RecuperarPasswordController::class,
    'restablecerPassword'
])->name('password.update');

// Verificacion de correo
Route::get('/verificacion-correo', function () {
    return view('html.login.verificacion-correo');
})->name('verificacion-correo');

/*
|--------------------------------------------------------------------------
| 3. PAGINAS PRIVADAS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Perfil de usuario
    Route::get('/perfil', function () {
        return view('html.login.perfil');
    })->name('perfil');

    Route::post('/perfil', [PerfilController::class, 'actualizar'])
        ->name('perfil.actualizar');

    // Cerrar sesion
    Route::post('/logout', [LoginController::class, 'cerrarSesion'])
        ->name('logout');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('html.dashboard.dashboard');
    })->name('dashboard');

    // Modulos de aprendizaje
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

    // Rutas por area profesional
    Route::get('/ruta-sistemas', function () {
        return view('html.dashboard.ruta-sistemas');
    })->name('ruta-sistemas');

    Route::get('/ruta-medicina', function () {
        return view('html.dashboard.ruta-medicina');
    })->name('ruta-medicina');

    Route::get('/ruta-gastronomia', function () {
        return view('html.dashboard.ruta-gastronomia');
    })->name('ruta-gastronomia');

    Route::get('/ruta-derecho', function () {
        return view('html.dashboard.ruta-derecho');
    })->name('ruta-derecho');

    Route::get('/ruta-arquitectura', function () {
        return view('html.dashboard.ruta-arquitectura');
    })->name('ruta-arquitectura');

    Route::get('/ruta-general', function () {
        return view('html.dashboard.ruta-general');
    })->name('ruta-general');

    // Administracion de ejercicios (CRUD)
    Route::resource('gestion/ejercicios', EjercicioController::class)
        ->except('show')
        ->parameters(['gestion/ejercicios' => 'ejercicio'])
        ->names([
            'index' => 'gestion.ejercicios.index',
            'create' => 'gestion.ejercicios.create',
            'store' => 'gestion.ejercicios.store',
            'edit' => 'gestion.ejercicios.edit',
            'update' => 'gestion.ejercicios.update',
            'destroy' => 'gestion.ejercicios.destroy',
        ]);

});
