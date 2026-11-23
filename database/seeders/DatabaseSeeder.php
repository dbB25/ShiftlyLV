<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Datos de prueba de todas las tablas, con el alumno prueba@shiftly.com
 * (contraseña Password123). Se puede correr varias veces sin duplicar datos.
 *
 *   php artisan db:seed                        → todas las tablas
 *   php artisan db:seed --class=DiagnosticoSeeder   → solo una tabla
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Usuario y contenido del panel de gestión
            UsuarioSeeder::class,
            AreaSeeder::class,
            LeccionSeeder::class,
            TerminoSeeder::class,
            EjercicioSeeder::class,
            ProgresoSeeder::class,

            // Avance del alumno en las rutas (el orden importa por las llaves foráneas)
            ProgresoRutaSeeder::class,
            ProgresoLeccionSeeder::class,
            ErrorEjercicioSeeder::class,
            ActividadDiariaSeeder::class,
            LeccionPersonalizadaSeeder::class,
            PracticaLibreSeeder::class,
            DiagnosticoSeeder::class,
        ]);
    }
}
