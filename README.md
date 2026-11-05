# Shiftly

Shiftly es una app web gamificada de inglés profesional que prepara a trabajadores para laborar en el extranjero. Enseña vocabulario técnico, jerga laboral e modismos corporativos complejos mediante lecciones interactivas, ayudando a los usuarios a comunicarse con total confianza y evitar malentendidos en su día a día.

---

## 👥 Integrantes del Equipo

- **Luis David Balandrano Delgado** — *Líder Técnico*
- **Keydhy Mareli Villa Aldana** — *Analista*
- **Isis Dayane Arellano Carrillo** — *Frontend*
- **Brian Jael Juárez Rodrigues** — *Frontend*
- **Julio Cesar Licona Hernandez** — *Backend*
- **Vanesa Lugo Ramírez** — *Backend*

---

## 📋 Descripción de la Aplicación

Shiftly brinda una experiencia de aprendizaje personalizada y gamificada. Permite a los usuarios seleccionar un área profesional de interés, evaluar su nivel mediante un test de diagnóstico inicial[cite: 3], realizar ejercicios prácticos iterativos[cite: 4], consultar un glosario con biblioteca de repaso por sectores y dar seguimiento a su racha y progreso dentro de un panel de control dedicado[cite: 2].

---

## 🎯 Objetivo

Desarrollar una plataforma web responsiva, accesible e interactiva construida con Bootstrap[cite: 1, 2, 3, 4] que facilite la capacitación en inglés técnico y corporativo, preparando profesionalmente a los trabajadores para desenvolverse sin barreras idiomáticas en el ámbito laboral extranjero.

---

## 🛠️ Tecnologías Utilizadas

- **HTML5:** Estructuración de páginas y componentes semánticos[cite: 1, 2, 3, 4].
- **CSS3 (Custom Styles):** Reglas de diseño personalizadas, variables CSS (`--color-primario`, `--color-secundario`, etc.) y ajustes tipográficos.
- **Bootstrap v5.3.3:** Framework de CSS para diseño adaptable (*Mobile-First*) y maquetación mediante *Grid System*[cite: 1, 2, 3, 4].
- **Bootstrap Icons v1.11.3:** Colección de iconografía para la interfaz de usuario[cite: 1].
- **Google Fonts:** Tipografías integradas (`Poppins` y `Playfair Display`)[cite: 4].
- **JavaScript (ES6+):** Scripts de interacción para selección de áreas y componentes dinámicos de Bootstrap[cite: 1, 2, 3].

---

## ⭐ Características Principales

- **Selección de Área Profesional:** Selección de contexto técnico (Programación, Gastronomía, Turismo, Negocios e Inglés Cotidiano).
- **Módulo de Diagnóstico:** Evaluación interactiva con cálculo del nivel B1 (Intermedio) y recomendaciones de estudio[cite: 3].
- **Práctica Gamificada e Interactiva:** Ejercicios dinámicos con banco de palabras, opción múltiple, emparejamiento y llenado de frases[cite: 4].
- **Dashboard de Aprendizaje:** Seguimiento de racha diaria, porcentaje de progreso general y desglose por área (Vocabulario, Gramática, Comprensión, Conversación)[cite: 2].
- **Biblioteca y Glosario por Sectores:** Tarjetas de repaso con ocultamiento de traducción (`<details>`), ejemplos de uso y soporte de pronunciación.
- **Módulo de Autenticación Completo:** Vistas para Inicio de Sesión, Registro, Recuperación de Contraseña y Verificación por Código de 4 Dígitos.
- **Página de Contacto e Integración Geográfica:** Mapa interactivo de ubicación embebido mediante Google Maps[cite: 1].
- **Perfil de Usuario:** Gestión de datos personales y estado de la cuenta.

---

## 🧩 Componentes de Bootstrap Utilizados

- **Navbar:** Menús de navegación superiores fijos (*sticky-top*) y colapsables con menú hamburguesa[cite: 1, 2].
- **Grid System (Rows & Cols):** Sistema de maquetación adaptable mediante contenedores y columnas (`row`, `col-lg-*`, `col-md-*`)[cite: 1, 2, 3, 4].
- **Cards:** Tarjetas contenedoras para estadísticas[cite: 2], preguntas de diagnóstico[cite: 3], ejercicios[cite: 4], tarjetas de vocabulario, perfiles y formularios[cite: 1].
- **Progress Bars:** Barras para la representación visual del avance en lecciones y métricas del dashboard[cite: 2, 3, 4].
- **Badges:** Identificadores y etiquetas de estado (*"USUARIO ACTIVO"*, *"Sector Técnico"*, *"Medicina & Salud"*).
- **Buttons & Button Groups:** Botones de acción directos, filtros con píldoras (`rounded-pill`) y variantes de contorno (`btn-outline-*`)[cite: 1, 2, 3].
- **Forms & Input Groups:** Formularios con iconos integrados en los campos de texto[cite: 1].

---

## 📁 Estructura del Proyecto


Shiftly/
│
├── img_log/
│   └── logotipo interfaz.svg     # Logotipo oficial de la aplicación

│
├── img/                          # Capturas de las interfaces
│   ├── ruta-aprendizaje-por-areas.png
│   ├── ruta-aprendizaje-recomendacion.png
│   ├── resultados-diagnostico.png
│   └── resultados-leccion.png
│
├── index.html                    # Selección de área de aprendizaje
├── login.html                    # Formulario de inicio de sesión
├── registro.html                 # Registro de usuarios
├── recuperar-password.html        # Recuperación de acceso
├── verificacion-correo.html      # Confirmación por código de 4 dígitos
├── dashboard.html                # Panel de control y estadísticas del usuario
├── diagnostico.html              # Test de diagnóstico de nivel
├── resultado.html                # Resultado del diagnóstico inicial
├── Ejercicios.html               # Módulo interactivo de lecciones y ejercicios
├── resultados-leccion.html       # Desglose de puntuación al finalizar una lección
├── glosario.html                 # Biblioteca y repaso técnico por sectores
├── servicios.html                # Tarjetas de vocabulario técnico detallado
├── perfil.html                   # Configuración del perfil de usuario
├── contacto.html                 # Formulario de contacto y mapa interactivo
│
├── style.css                     # Hoja de estilos global y variables del proyecto
├── Style de ejercicios.css      # Estilos dedicados al módulo de ejercicios
└── README.md                     # Documentación del proyecto
interactividad-a-diagnostico-js


## 🖼️ Capturas de la interfaz

Las capturas disponibles de las interfaces se guardan en `img/`:

- **Ruta de aprendizaje por áreas:** ![Ruta de aprendizaje por áreas](img/ruta-aprendizaje-por-areas.png)
- **Ruta de aprendizaje con recomendación:** ![Ruta de aprendizaje con recomendación](img/ruta-aprendizaje-recomendacion.png)
- **Resultados del diagnóstico:** ![Resultados del diagnóstico](img/resultados-diagnostico.png)
- **Resultados de la lección:** ![Resultados de la lección](img/resultados-leccion.png)


## Evidencias de interfaz

1.- Panel del Usuario

El panel del usuario permite acceder de manera rápida a las principales funcionalidades de la plataforma Shiftly. La interfaz cuenta con un menú de navegación, accesos directos a los módulos de aprendizaje y resultados, y un formulario para editar los datos del perfil del usuario.

2.- Test de Diagnóstico de Inglés

La interfaz del test de diagnóstico permite a los usuarios evaluar su nivel actual de inglés mediante una serie de preguntas de opción múltiple. A partir de las respuestas, la plataforma busca orientar al usuario hacia recursos y recomendaciones de aprendizaje adecuados a sus necesidades.


3.- Biblioteca y Repaso Técnico

La interfaz de Biblioteca y Repaso Técnico permite a los usuarios explorar y consultar vocabulario relacionado con diferentes sectores profesionales. Cuenta con un buscador para localizar conceptos o lecciones específicas, así como filtros por área de conocimiento.

4.- Panel de Ruta de Aprendizaje y Recomendaciones

Esta interfaz presenta un panel de seguimiento del aprendizaje que permite al usuario consultar la siguiente lección recomendada, revisar sus módulos recientes y visualizar su progreso dentro de la plataforma Shiftly.

5.- Resultados del Diagnóstico de Inglés

Esta interfaz presenta los resultados obtenidos al finalizar el test de diagnóstico de inglés. Muestra el nivel estimado del usuario, una descripción de sus conocimientos y una recomendación general para continuar mejorando sus habilidades lingüísticas.

6.- Resultados de la Lección

Esta interfaz permite al usuario consultar la puntuación obtenida después de completar una lección y acceder al detalle de sus resultados en diferentes sectores profesionales.

7.- Ruta de Aprendizaje

Esta interfaz permite al usuario visualizar las áreas profesionales disponibles para estudiar y llevar un seguimiento de su progreso dentro de la plataforma Shiftly. Cada área cuenta con una descripción de los contenidos que se pueden aprender y una opción para marcarla como completada.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# ShiftlyLV
Shiftly es una app web gamificada de inglés profesional que prepara a trabajadores para laborar en el extranjero. Enseña vocabulario técnico, jerga laboral e modismos corporativos complejos mediante lecciones interactivas, ayudando a los usuarios a comunicarse con total confianza y evitar malentendidos en su día a día.
