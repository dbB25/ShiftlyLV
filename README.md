# Shiftly

Shiftly es una app web gamificada de inglés profesional que prepara a trabajadores para laborar en el extranjero. Enseña vocabulario técnico, jerga laboral y modismos corporativos mediante lecciones interactivas, para que los usuarios se comuniquen con confianza y eviten malentendidos en su día a día.

Proyecto de la materia **Aplicaciones Web** (Septiembre–Diciembre 2026): aplicación CRUD con **Laravel 13** y **PostgreSQL**.

---

## Índice

1. [Integrantes del equipo](#integrantes-del-equipo)
2. [Requisitos](#requisitos)
3. [Instalación](#instalación)
4. [Actualizar después de un `git pull`](#actualizar-después-de-un-git-pull)
5. [Panel de contenido (CRUD)](#panel-de-contenido-crud)
6. [Rutas principales](#rutas-principales)
7. [Modelo de datos](#modelo-de-datos)
8. [Validación y seguridad](#validación-y-seguridad)
9. [Pruebas](#pruebas)
10. [Cumplimiento de la rúbrica](#cumplimiento-de-la-rúbrica)
11. [Solución de problemas](#solución-de-problemas)
12. [Flujo de trabajo con Git](#flujo-de-trabajo-con-git)
13. [Tecnologías](#tecnologías)
14. [Características principales](#características-principales)
15. [Estructura del proyecto](#estructura-del-proyecto)
16. [Evidencias de interfaz](#evidencias-de-interfaz)
17. [Historial de integración](#historial-de-integración)
18. [Pendientes y decisiones abiertas](#pendientes-y-decisiones-abiertas)

---

## Integrantes del equipo

| Integrante | Rol |
| --- | --- |
| Luis David Balandrano Delgado | Líder técnico |
| Keydhy Mareli Villa Aldana | Analista |
| Isis Dayane Arellano Carrillo | Frontend |
| Brian Jael Juárez Rodrigues | Frontend |
| Julio Cesar Licona Hernandez | Backend |
| Vaneza Lugo Ramírez | Backend |

---

## Requisitos

- PHP 8.3 o superior con las extensiones `pdo_pgsql` y `pgsql` (y `pdo_sqlite` y `sqlite3` para correr las pruebas)
- Composer 2
- PostgreSQL 14 o superior (el proyecto se probó con PostgreSQL 18)
- Git

> En XAMPP las extensiones se activan en `C:\xampp\php\php.ini` agregando o descomentando:
>
> ```ini
> extension=pdo_pgsql
> extension=pgsql
> extension=pdo_sqlite
> extension=sqlite3
> ```
>
> Después reinicia Apache desde el panel de XAMPP. Para comprobarlo: `php -m` debe listar `pdo_pgsql` y `pdo_sqlite`.

No se necesita Node.js: las vistas cargan Bootstrap desde CDN y no usan Vite.

---

## Instalación

1. **Clonar el repositorio**

   ```bash
   git clone https://github.com/dbB25/ShiftlyLV.git
   cd ShiftlyLV
   ```

2. **Instalar las dependencias de PHP**

   ```bash
   composer install
   ```

3. **Crear el archivo de entorno y la llave de la aplicación**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Crear la base de datos en PostgreSQL** (desde pgAdmin: *Databases → Create → Database*, o con `psql`):

   ```sql
   CREATE DATABASE shiftly;
   ```

   Puedes usar otro nombre (por ejemplo `shiftly_db`); solo pon el mismo en `DB_DATABASE`.

5. **Configurar la conexión en `.env`** con tu usuario y contraseña de PostgreSQL:

   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=shiftly
   DB_USERNAME=postgres
   DB_PASSWORD=tu_contraseña
   ```

   El archivo `.env` está en `.gitignore` y no se sube al repositorio. Nunca guardes contraseñas reales en `.env.example`.

6. **Crear las tablas y cargar los datos de prueba**

   ```bash
   php artisan migrate --seed
   ```

   Para borrar todo y empezar de cero: `php artisan migrate:fresh --seed`.

7. **Levantar el servidor**

   ```bash
   php artisan serve
   ```

   Abre <http://127.0.0.1:8000>.

### Usuario de prueba

El seeder crea un usuario para entrar al dashboard y al panel de contenido:

| Correo | Contraseña |
| --- | --- |
| `prueba@shiftly.com` | `Password123` |

### Datos que carga el seeder

| Tabla | Registros |
| --- | --- |
| `users` | 1 (usuario de prueba) |
| `areas` | 6 (Sistemas y Software, Medicina y Salud, Gastronomía, Derecho Corporativo, Arquitectura, General) |
| `lecciones` | 6 (una «Introducción a…» por área) |
| `ejercicios` | 6 (uno por lección) |
| `terminos` | 6 (uno por área) |
| `progresos` | 6 (progreso del usuario de prueba en cada lección) |

### Recuperación de contraseña

`.env.example` usa `MAIL_MAILER=log`: el correo con el enlace para restablecer la contraseña no se envía, se escribe en `storage/logs/laravel.log`. Para enviarlo de verdad, configura un servidor SMTP en las variables `MAIL_*` de tu `.env`.

---

## Actualizar después de un `git pull`

Cada vez que traigas cambios de `main`:

```bash
git checkout main
git pull origin main
composer install
php artisan migrate
```

Si cambió la estructura de una tabla que ya tenías (por ejemplo `ejercicios`) o aparece un error de tabla duplicada, reinicia la base:

```bash
php artisan migrate:fresh --seed
```

> `migrate:fresh` borra **todas** las tablas y sus datos antes de volver a crearlas. Úsalo solo en tu base local.

---

## Panel de contenido (CRUD)

Después de iniciar sesión, el panel está en <http://127.0.0.1:8000/gestion/lecciones>. También se llega desde el dashboard (botón **Panel de contenido**) y desde el menú superior del panel.

| Módulo | Ruta | Operaciones |
| --- | --- | --- |
| Lecciones | `/gestion/lecciones` | Crear, listar (10 por página, búsqueda por título y filtro por área), editar y eliminar |
| Ejercicios | `/gestion/ejercicios` | Crear, listar (10 por página, búsqueda por título o pregunta y filtro por lección), editar y eliminar |

- Las rutas usan `Route::resource()` con *route model binding* y están dentro del middleware `auth`.
- Los formularios de crear y editar comparten un parcial `_form.blade.php` y precargan los datos actuales al editar.
- Cada ejercicio pertenece a una lección (campo **Lección** obligatorio).
- La búsqueda no distingue mayúsculas (en PostgreSQL usa `ILIKE`) y se conserva al cambiar de página.
- Al eliminar se abre una ventana de confirmación:
  - una lección con **progreso de estudiantes** no se elimina y se muestra un mensaje;
  - una lección sin progreso se elimina junto con sus ejercicios, y la ventana lo avisa antes.
- Después de crear, editar o eliminar aparece un mensaje de éxito o de error.

---

## Rutas principales

| Método | Ruta | Descripción | Acceso |
| --- | --- | --- | --- |
| GET | `/` | Inicio y selección de área | Público |
| GET / POST | `/registro` | Crear cuenta | Público |
| GET / POST | `/login` | Iniciar sesión | Público |
| POST | `/logout` | Cerrar sesión | Con sesión |
| GET / POST | `/recuperar-password` | Pedir enlace para restablecer la contraseña | Público |
| GET | `/new-password/{token}` | Formulario para restablecer la contraseña | Público |
| POST | `/new-password` | Guardar la nueva contraseña | Público |
| GET / POST | `/contacto` | Formulario de contacto | Público |
| GET | `/servicios` | Vocabulario por sector | Público |
| GET | `/dashboard` | Panel del usuario | Con sesión |
| GET / POST | `/perfil` | Ver y editar el perfil | Con sesión |
| GET | `/diagnostico`, `/resultado`, `/recomendaciones` | Test de nivel y resultados | Con sesión |
| GET | `/ruta-aprendizaje`, `/ruta-{carrera}` | Árboles de lecciones por carrera | Con sesión |
| GET | `/ejercicios`, `/resultados-leccion`, `/glosario` | Práctica, resultados y glosario | Con sesión |
| RESOURCE | `/gestion/lecciones` | CRUD de lecciones | Con sesión |
| RESOURCE | `/gestion/ejercicios` | CRUD de ejercicios | Con sesión |

Lista completa: `php artisan route:list --except-vendor`.

---

## Modelo de datos

| Tabla | Descripción | Relaciones |
| --- | --- | --- |
| `users` | Usuarios de la plataforma (nombre, apellido, carrera, área profesional) | tiene muchos `progresos` |
| `areas` | Áreas profesionales | tiene muchas `lecciones` y `terminos` |
| `lecciones` | Lecciones de cada área, con nivel A1–C2, orden y estado publicada | pertenece a `areas`; tiene muchos `ejercicios` y `progresos` |
| `ejercicios` | Ejercicios de cada lección (completar, opción múltiple, ordenar, emparejar) | pertenece a `lecciones` |
| `terminos` | Glosario de términos por área | pertenece a `areas` |
| `progresos` | Puntaje y avance de cada usuario por lección | pertenece a `users` y `lecciones` |

### Llaves foráneas y política de borrado

| Llave foránea | Al borrar el registro padre | Motivo |
| --- | --- | --- |
| `lecciones.area_id → areas` | `RESTRICT` (no deja borrar el área) | Evita perder lecciones por accidente |
| `ejercicios.leccion_id → lecciones` | `CASCADE` | Un ejercicio no existe sin su lección |
| `terminos.area_id → areas` | `CASCADE` | El glosario depende de su área |
| `progresos.user_id → users` | `CASCADE` | El progreso es del usuario |
| `progresos.leccion_id → lecciones` | `CASCADE` | El progreso es de la lección (el panel bloquea antes ese borrado) |

### Restricciones principales

- `areas`: `nombre` y `slug` únicos (80 caracteres), `activo` boolean con valor por defecto `true`.
- `lecciones`: título único por área (`unique(area_id, titulo)`), `publicada` boolean con valor por defecto `false`.
- `ejercicios`: título único por lección (`unique(leccion_id, titulo)`), `opciones` en `jsonb`.
- `terminos`: término único por área (`unique(area_id, termino)`).
- `progresos`: un registro por usuario y lección (`unique(user_id, leccion_id)`), `puntaje` `decimal(5,2)`.

Las migraciones están en `database/migrations`, las factories en `database/factories` y los datos de prueba en `database/seeders/DatabaseSeeder.php`.

---

## Validación y seguridad

- **FormRequests** separados para crear y editar: `StoreLeccionRequest` / `UpdateLeccionRequest` y `StoreEjercicioRequest` / `UpdateEjercicioRequest`. También `RegistroRequest`, `PerfilRequest` y `ContactoRequest`.
- **Reglas** `required`, `max`, `integer`, `exists`, `Rule::in` y `unique`; al editar, `unique` ignora el registro actual.
- **Mensajes** de error personalizados en español, mostrados junto a cada campo con `@error`; los datos escritos se conservan con `old()`.
- **Asignación masiva protegida:** los modelos declaran `$fillable` y los controladores guardan solo `$request->validated()`.
- **CSRF:** todos los formularios que se envían al servidor incluyen `@csrf`, y `@method('PUT')` / `@method('DELETE')` donde corresponde.
- **Salida escapada** con `{{ }}`; no se usa `{!! !!}` con datos del usuario.
- **Consultas** con Eloquent y parámetros enlazados; no hay SQL concatenado.
- **Contraseñas** guardadas con hash (`'password' => 'hashed'` en el modelo `User`).
- **Rutas privadas** dentro del middleware `auth`; al iniciar sesión se regenera la sesión y al cerrarla se invalida.

---

## Pruebas

```bash
php artisan test
```

Hay **48 pruebas** (143 aserciones) en `tests/Feature`:

| Archivo | Qué comprueba |
| --- | --- |
| `GestionLeccionesTest.php` | Acceso solo con sesión, paginación, búsqueda, crear, validación en español, título único, editar y eliminar (con y sin progreso) |
| `GestionEjerciciosTest.php` | Paginación, búsqueda y filtro por lección, crear con opciones, validación, `old()`, editar y eliminar |
| `RegistroTest.php` | Registro con carrera, correo repetido y carrera inválida |
| `RutasTest.php` | Que todas las páginas cargan y traen sus scripts |

Las pruebas usan SQLite en memoria (ver `phpunit.xml`), así que no tocan tu base de PostgreSQL. Necesitan la extensión `pdo_sqlite`.

---

## Cumplimiento de la rúbrica

Qué se hizo en cada criterio del examen parcial, dónde está en el código y cómo comprobarlo.

| Criterio | Peso | Dónde se ve |
| --- | --- | --- |
| 1. Base de datos PostgreSQL y migraciones | 20 % | `.env.example`, `database/migrations`, `app/Models`, `DatabaseSeeder` |
| 2. Funcionalidad CRUD desde formularios | 30 % | `/gestion/lecciones`, `/gestion/ejercicios`, `LeccionController`, `EjercicioController` |
| 3. Validación de datos y seguridad | 20 % | `app/Http/Requests`, vistas de `resources/views/gestion` |
| 4. Arquitectura MVC y enrutamiento | 15 % | `routes/web.php`, `layouts/app.blade.php`, parciales `_form.blade.php` |
| 5. Interfaz de usuario y retroalimentación | 15 % | Formularios del panel, `partials/`, `public/css/gestion.css` |

### 1. Base de datos PostgreSQL y migraciones (20 %)

**Qué se hizo**

- `.env.example` configurado para PostgreSQL (`DB_CONNECTION=pgsql`, host, puerto 5432, base `shiftly` y usuario), sin contraseña real; `.env` está en `.gitignore`.
- 12 migraciones que crean todo desde cero: `users`, `areas`, `lecciones`, `ejercicios`, `terminos`, `progresos` y las tablas internas de Laravel (sesiones, caché y colas).
- Tipos de dato adecuados: `string` con longitud (`titulo` 150, `nombre` 80), `text`, `boolean` con valor por defecto, `decimal(5,2)` y `jsonb`.
- Restricciones `unique`, `nullable` y `default`, incluidos únicos compuestos: título por área, título por lección, término por área y progreso por usuario y lección.
- Llaves foráneas con `constrained()` y política de borrado explícita: `restrictOnDelete` en `lecciones.area_id` y `cascadeOnDelete` en las demás.
- Modelos `Area`, `Leccion`, `Ejercicio`, `Termino`, `Progreso` y `User` con `$fillable`, `casts` y relaciones `belongsTo` / `hasMany`.
- Factories para todos los modelos y un seeder con datos de prueba en las 6 áreas.
- Se corrigió una migración duplicada de `ejercicios` que hacía fallar `migrate:fresh`, y se dejó una sola tabla ligada a `lecciones`.

**Cómo comprobarlo**

```bash
php artisan migrate:fresh --seed
```

Debe terminar sin errores. En pgAdmin se ven las tablas, las llaves foráneas (*Constraints*) y los datos del seeder.

### 2. Funcionalidad CRUD desde formularios (30 %)

**Qué se hizo**

- CRUD completo en **dos entidades**: Lecciones (`/gestion/lecciones`) y Ejercicios (`/gestion/ejercicios`). Las cuatro operaciones se hacen desde formularios y guardan en PostgreSQL.
- Listados con **paginación** (10 por página), **búsqueda** sin distinguir mayúsculas y filtros por área o por lección; los filtros se conservan al cambiar de página.
- Editar precarga los datos actuales del registro.
- Eliminar pide confirmación en una ventana modal.
- Manejo de registros con relaciones:
  - una lección con progreso de estudiantes **no se elimina** y se muestra un mensaje controlado;
  - una lección sin progreso se elimina junto con sus ejercicios, y la ventana lo avisa antes;
  - si la base rechaza un borrado, el error se atrapa y se muestra un mensaje en lugar de un error 500.
- Se quitaron funciones que aparentaban ser CRUD pero no guardaban en la base de datos (administrador de cuentas del login y perfil simulado del dashboard) y un CRUD de productos ajeno al proyecto.

**Cómo comprobarlo**

Inicia sesión con el usuario de prueba, entra a `/gestion/lecciones`, crea una lección, búscala, edítala y elimínala. Intenta eliminar «Introducción a Sistemas y Software»: como tiene progreso, la app lo impide con un mensaje.

Capturas: [listado](public/img/capturas/11-lecciones-listado.jpg), [confirmar eliminación](public/img/capturas/15-lecciones-confirmar-eliminar.jpg), [eliminación bloqueada](public/img/capturas/16-lecciones-eliminar-bloqueado.jpg) y [búsqueda de ejercicios](public/img/capturas/17-ejercicios-busqueda.jpg).

### 3. Validación de datos y seguridad (20 %)

**Qué se hizo**

- FormRequests **independientes** para crear y editar: `StoreLeccionRequest` / `UpdateLeccionRequest` y `StoreEjercicioRequest` / `UpdateEjercicioRequest`.
- Reglas pertinentes: `required`, `max`, `integer`, `min`, `exists:areas,id`, `exists:lecciones,id`, `Rule::in` y `unique`.
- Al editar, la regla `unique` **ignora el registro actual** (`Rule::unique(...)->ignore(...)`).
- Mensajes de error personalizados en español para cada regla.
- Validación que depende de varios campos (por ejemplo, que la respuesta correcta esté entre las opciones) en el método `after()` del FormRequest.
- Registro, perfil y contacto también validan con FormRequests; el registro valida y guarda la carrera.
- Asignación masiva protegida: `$fillable` en los modelos y `$request->validated()` en los controladores.
- Todos los formularios que se envían al servidor llevan `@csrf`, y `@method('PUT')` / `@method('DELETE')` donde corresponde.
- La salida se escapa con `{{ }}` y las consultas usan Eloquent con parámetros enlazados.

**Cómo comprobarlo**

Envía el formulario de nueva lección vacío: cada campo muestra su error en español. Crea dos lecciones con el mismo título en la misma área: la segunda se rechaza. Edita una lección sin cambiar su título: se guarda sin error.

Capturas: [errores de validación](public/img/capturas/12-lecciones-validacion.jpg) y [registro](public/img/capturas/03-registro.jpg).

### 4. Arquitectura MVC y enrutamiento (15 %)

**Qué se hizo**

- Rutas RESTful con `Route::resource()` agrupadas bajo `Route::prefix('gestion')->name('gestion.')`, con nombres convencionales (`gestion.lecciones.index`, `gestion.ejercicios.store`, etc.).
- *Route model binding*: los métodos reciben el modelo directamente (`edit(Leccion $leccion)`).
- Rutas privadas dentro del middleware `auth`; login, registro, perfil, contacto y recuperación de contraseña conectados a sus controladores.
- Controladores delgados: la validación está en los FormRequests y la búsqueda en *scopes* de los modelos (`scopeBuscar`).
- Vistas con layout base `layouts/app.blade.php` (`@extends`, `@section`, `@stack`) y un parcial `_form.blade.php` compartido por create y edit en cada módulo.
- Parciales reutilizables para alertas y para la ventana de confirmación de eliminación.
- Carpetas ordenadas por módulo (`resources/views/gestion/lecciones`, `resources/views/gestion/ejercicios`) y nombres en PascalCase y camelCase (PSR-12).
- Se corrigieron conflictos de merge que dejaban la app con error de sintaxis y rutas duplicadas.

**Cómo comprobarlo**

```bash
php artisan route:list --path=gestion
```

Muestra las 12 rutas del CRUD con sus nombres y controladores.

Capturas: [panel con el layout común](public/img/capturas/11-lecciones-listado.jpg) y [formulario `_form` al editar](public/img/capturas/13-lecciones-editar.jpg).

### 5. Interfaz de usuario y retroalimentación (15 %)

**Qué se hizo**

- Formularios claros: etiqueta asociada a cada campo, tipos de input adecuados (`select`, `number`, `textarea`, interruptor) y `*` en los obligatorios.
- Botón **Cancelar** al crear y al editar, y enlace para volver al listado.
- Errores de validación junto a cada campo con `@error` y la clase `is-invalid`; los datos escritos se conservan con `old()`.
- Mensajes flash de éxito y de error tras crear, editar y eliminar, con `session()` y el parcial `alertas.blade.php`.
- Diseño consistente en todo el panel gracias al layout común y a `public/css/gestion.css`.
- Diseño responsivo: en móvil las tablas ocultan las columnas secundarias para no desbordarse.
- El dashboard muestra los datos reales de la cuenta y enlaza a `/perfil` y al panel de contenido.

**Cómo comprobarlo**

Provoca un error de validación y revisa que el mensaje aparezca junto al campo con lo que habías escrito. Crea, edita y elimina un registro para ver los mensajes de éxito. Reduce la ventana al tamaño de un teléfono para ver el diseño responsivo.

Capturas: [mensaje de éxito](public/img/capturas/14-lecciones-mensaje-exito.jpg), [errores junto al campo](public/img/capturas/12-lecciones-validacion.jpg) y [vista en móvil](public/img/capturas/19-ejercicios-movil.jpg).

### Penalizaciones evitadas

| Penalización | Cómo se evita |
| --- | --- |
| Error crítico de conexión | `.env.example` para PostgreSQL y migraciones completas; se instala siguiendo este README |
| Exposición de credenciales | `.env` en `.gitignore`; `.env.example` sin contraseña |
| Repositorio sin historial | Commits de varios integrantes, organizados en ramas y pull requests |
| Falta de documentación | Este README con requisitos, instalación, pruebas y solución de problemas |

---

## Solución de problemas

| Síntoma | Causa | Solución |
| --- | --- | --- |
| `could not find driver` | Falta la extensión de PHP | Activa `pdo_pgsql` (app) o `pdo_sqlite` (pruebas) en `php.ini` y reinicia Apache |
| `password authentication failed for user "postgres"` | Contraseña incorrecta en `.env` | Revisa `DB_USERNAME` y `DB_PASSWORD`; luego `php artisan config:clear` |
| `database "shiftly" does not exist` | No se creó la base | Créala en pgAdmin con el mismo nombre que `DB_DATABASE` |
| `relation "..." already exists` o columnas que no coinciden | Tu base tiene tablas de una versión anterior | `php artisan migrate:fresh --seed` |
| `No application encryption key has been specified` | Falta `APP_KEY` | `php artisan key:generate` |
| Los cambios del `.env` no se aplican | Configuración en caché | `php artisan config:clear` |
| `vendor/autoload.php` no existe | Faltan dependencias | `composer install` |
| Una página redirige al login | La ruta es privada | Inicia sesión con el usuario de prueba |

---

## Flujo de trabajo con Git

1. Antes de empezar, actualiza `main`: `git checkout main` y `git pull origin main`.
2. Crea una rama para tu tarea: `git checkout -b feature/nombre-de-la-tarea`.
3. Haz commits pequeños con tu propia cuenta y mensajes claros.
4. Antes de abrir el PR, vuelve a traer `main` (`git pull origin main`) y resuelve los conflictos en tu rama.
5. Comprueba que no queden marcas de conflicto (`<<<<<<<`, `=======`, `>>>>>>>` o líneas sueltas con el nombre de una rama) y que `php artisan test` y `php artisan migrate:fresh --seed` terminen sin errores.
6. Abre el PR hacia `main` con una descripción de qué cambió y cómo probarlo.

No subas `.env`, archivos `.bak`, la base de datos SQLite ni archivos temporales del editor (`storage/framework/lsp-*`); ya están en `.gitignore`.

---

## Tecnologías

- **Laravel 13** (PHP 8.3+): rutas, controladores, Eloquent, migraciones, FormRequests y Blade
- **PostgreSQL**: base de datos
- **Bootstrap 5.3** y **Bootstrap Icons 1.11**: diseño responsivo
- **JavaScript (ES6+)**: interactividad de lecciones, diagnóstico y progreso
- **PWA**: `public/manifest.webmanifest` y `public/sw.js` para instalar la app
- **Google Fonts**: Plus Jakarta Sans, Poppins y Playfair Display
- **PHPUnit**: pruebas automatizadas

---

## Características principales

- **Selección de área profesional:** Sistemas, Medicina, Gastronomía, Derecho, Arquitectura y General.
- **Diagnóstico:** test de nivel con resultado y recomendaciones de estudio.
- **Práctica gamificada:** ejercicios con banco de palabras, opción múltiple, emparejamiento y completar frases.
- **Árboles de lecciones por carrera:** avance, XP y lecciones personalizadas guardadas en el navegador.
- **Dashboard:** racha diaria, progreso general, desglose por área y datos reales de la cuenta.
- **Glosario por sectores:** tarjetas de repaso con traducción, ejemplos y pronunciación.
- **Autenticación:** registro con carrera, inicio de sesión, recuperación de contraseña y verificación de correo.
- **Perfil de usuario:** edición de nombre, apellido y área profesional guardada en la base de datos.
- **Contacto:** formulario con validación y mapa de ubicación.
- **Panel de contenido:** CRUD de lecciones y ejercicios guardados en PostgreSQL.

---

## Estructura del proyecto

```text
app/
├── Http/Controllers/       # LeccionController, EjercicioController, Login, Registro, Perfil, Contacto, RecuperarPassword
├── Http/Requests/          # Store/Update de lecciones y ejercicios, Registro, Perfil y Contacto
├── Models/                 # User, Area, Leccion, Ejercicio, Termino, Progreso
└── Providers/              # AppServiceProvider (paginación con Bootstrap 5)
database/
├── factories/              # Datos falsos para pruebas
├── migrations/             # Estructura de las tablas
└── seeders/                # Datos de prueba
public/
├── css/                    # Estilos por página (gestion.css para el panel)
├── js/                     # Lógica de lecciones, diagnóstico, progreso y PWA
└── img/                    # Logotipo, capturas e íconos de la PWA
resources/views/
├── layouts/app.blade.php   # Layout base del panel de contenido
├── partials/               # Alertas y ventana de confirmación de eliminación
├── gestion/lecciones/      # index, create, edit y _form
├── gestion/ejercicios/     # index, create, edit y _form
└── html/                   # Páginas públicas, autenticación y dashboard
routes/web.php              # Rutas de la aplicación
tests/Feature/              # Pruebas del CRUD, del registro y de las rutas
tools/generar-rutas/        # Generador en Python de las vistas, CSS y JS de las rutas por carrera
```

---

## Evidencias de interfaz

Capturas tomadas de la aplicación funcionando con PostgreSQL y los datos del seeder. Están en `public/img/capturas`.

### Páginas públicas y autenticación

| Inicio | Iniciar sesión | Crear cuenta |
| --- | --- | --- |
| ![Inicio](public/img/capturas/01-inicio.jpg) | ![Iniciar sesión](public/img/capturas/02-login.jpg) | ![Crear cuenta](public/img/capturas/03-registro.jpg) |
| Selección de área y acceso al test de nivel. | Formulario con `@csrf`, errores por campo y botón Cancelar / Volver. | Registro con carrera, validado por `RegistroRequest`. |

### Cuenta del usuario

| Dashboard | Dashboard: Mi cuenta | Perfil |
| --- | --- | --- |
| ![Dashboard](public/img/capturas/04-dashboard.jpg) | ![Mi cuenta](public/img/capturas/05-dashboard-mi-cuenta.jpg) | ![Perfil](public/img/capturas/06-perfil.jpg) |
| Progreso global, racha y meta diaria. | Datos reales de la cuenta, con enlaces a editar el perfil y al panel de contenido. | Edición de nombre, apellido y área profesional guardada en la base de datos. |

### Aprendizaje

| Diagnóstico | Ruta por carrera | Práctica | Glosario |
| --- | --- | --- | --- |
| ![Diagnóstico](public/img/capturas/07-diagnostico.jpg) | ![Ruta de Sistemas](public/img/capturas/08-ruta-sistemas.jpg) | ![Práctica](public/img/capturas/09-practicar.jpg) | ![Glosario](public/img/capturas/10-glosario.jpg) |
| Test de nivel por área profesional. | Árbol de lecciones con XP y lecciones personalizadas. | Ejercicios interactivos con pistas y audio. | Vocabulario por sector con buscador y filtros. |

### Panel de contenido: Lecciones

| Listado con búsqueda y filtro | Errores de validación | Editar con datos precargados |
| --- | --- | --- |
| ![Listado de lecciones](public/img/capturas/11-lecciones-listado.jpg) | ![Validación](public/img/capturas/12-lecciones-validacion.jpg) | ![Editar lección](public/img/capturas/13-lecciones-editar.jpg) |
| Paginación, búsqueda por título, filtro por área, conteo de ejercicios y estado. | Mensajes en español junto a cada campo (`@error`) y `*` en obligatorios. | El formulario `_form` compartido carga los datos actuales; botón Cancelar. |

| Mensaje de éxito | Confirmar eliminación | Eliminación bloqueada |
| --- | --- | --- |
| ![Mensaje de éxito](public/img/capturas/14-lecciones-mensaje-exito.jpg) | ![Confirmar eliminación](public/img/capturas/15-lecciones-confirmar-eliminar.jpg) | ![Eliminación bloqueada](public/img/capturas/16-lecciones-eliminar-bloqueado.jpg) |
| Mensaje flash tras crear, editar o eliminar. | Ventana que avisa si se borrarán ejercicios o si hay progreso. | Una lección con progreso de estudiantes no se elimina: mensaje controlado. |

### Panel de contenido: Ejercicios

| Búsqueda | Editar ejercicio | Vista en móvil |
| --- | --- | --- |
| ![Búsqueda de ejercicios](public/img/capturas/17-ejercicios-busqueda.jpg) | ![Editar ejercicio](public/img/capturas/18-ejercicios-editar.jpg) | ![Ejercicios en móvil](public/img/capturas/19-ejercicios-movil.jpg) |
| Búsqueda por título o pregunta sin distinguir mayúsculas y filtro por lección. | Cada ejercicio pertenece a una lección; opciones una por línea. | En móvil se ocultan columnas secundarias para no desbordar. |

### Diseños del prototipo

Capturas de las primeras versiones de la interfaz, conservadas como referencia.

| Interfaz | Descripción |
| --- | --- |
| ![Panel del usuario](public/img/Panel-usuario.jpeg) | **Panel del usuario:** acceso rápido a los módulos de aprendizaje y resultados. |
| ![Test de diagnóstico](public/img/Test-diagnostico.jpeg) | **Test de diagnóstico:** preguntas de opción múltiple para estimar el nivel de inglés. |
| ![Biblioteca y repaso](public/img/Biblioteca-repaso.jpeg) | **Biblioteca y repaso técnico:** vocabulario por sector con buscador y filtros. |
| ![Ruta de aprendizaje](public/img/Ruta-aprendisaje.jpeg) | **Ruta de aprendizaje y recomendaciones:** siguiente lección recomendada y progreso. |
| ![Resultados del diagnóstico](public/img/Resultados-diagnostico.jpeg) | **Resultados del diagnóstico:** nivel estimado y recomendación. |
| ![Resultados de la lección](public/img/Resultados-leccion.jpeg) | **Resultados de la lección:** puntuación y detalle por sector. |
| ![Ruta por áreas](public/img/Ruta-aprendisaj-areas.jpeg) | **Ruta por áreas:** áreas disponibles y seguimiento del avance. |

---

## Historial de integración

| PR / rama | Aporte |
| --- | --- |
| #22 `feature/interfaz-usuario-retroalimentacion` | Formularios con etiquetas, `*` en obligatorios, errores por campo con `@error`, `old()`, alertas globales y botones Cancelar/Volver |
| #18 `feature/crud-ejercicios-pr` | Primer CRUD de ejercicios y corrección de la navegación del diagnóstico hacia las rutas de Laravel |
| #19 `feature/registro-cuentas` | FormRequests, registro, inicio de sesión y perfil conectados a sus controladores; campo de área profesional |
| #21 `validacion-recuperacion` | Actualización de `composer.lock` |
| #23 `feature/postgresql-migrations-pr2` (incluye #20) | Configuración de PostgreSQL; tablas `areas`, `lecciones`, `terminos`, `ejercicios` y `progresos`; modelos con relaciones, factories y seeder; campo `carrera` |
| `fix/conflictos-merge` | Corrección de conflictos de merge; una sola migración de ejercicios ligada a lecciones; CRUD de lecciones; paginación y búsqueda; FormRequests de crear/editar; layout Blade; pruebas; este README |

Durante la integración se quitaron funciones que no guardaban en la base de datos o no pertenecían al proyecto: el administrador de cuentas del login (usaba `localStorage` y mostraba contraseñas), el formulario de perfil simulado del dashboard (ahora muestra los datos reales y enlaza a `/perfil`) y un CRUD de productos ajeno a Shiftly.

---

## Pendientes y decisiones abiertas

- **`carrera` y `area_profesional`:** la tabla `users` tiene las dos columnas y guardan un dato parecido (el registro usa `carrera`; el perfil, `area_profesional`). Conviene unificarlas en una sola, idealmente como llave foránea a `areas`.
- **Layout en las páginas anteriores:** el panel de contenido usa `layouts/app.blade.php`; las páginas públicas, de autenticación y del dashboard todavía tienen su propio HTML completo.
- **Progreso del juego:** el avance de los árboles de lecciones, el diagnóstico y las lecciones personalizadas se guarda en `localStorage`; la tabla `progresos` ya existe para pasarlo a la base de datos.
- **Verificación de correo:** la pantalla funciona en el navegador, pero todavía no valida el código contra el servidor.
