/* ==========================================================
   SHIFTLY · PROGRESO GLOBAL (window.ShiftlyProgreso)
   Lee las 6 claves shiftly_progreso_<carrera> y calcula:
   - resumen por carrera y global (XP, lecciones, siguiente lección)
   - racha diaria y meta del día (clave "shiftly_actividad")
   - insignias
   - diagnóstico → carrera y lección inicial recomendadas
   Requiere rutas-catalogo.js (window.SHIFTLY_CATALOGO).
   Todo es por usuario activo (shiftly_usuario_activo), igual que las rutas.
   Los datos vienen de la base de datos y cada cambio se guarda allí
   mediante shiftly-servidor.js (window.ShiftlyServidor).
   ========================================================== */
(function (global) {
    "use strict";

    const SESSION_KEY = "shiftly_usuario_activo";
    const PERFIL_KEY = "shiftly_user_profile_full";
    const ACTIVIDAD_KEY = "shiftly_actividad";
    const DIAGNOSTICO_KEY = "shiftlyDiagnosticoResultado";
    const META_POR_DEFECTO = 30;
    const CATALOGO = global.SHIFTLY_CATALOGO || [];

    // Lección en la que conviene empezar según el nivel del diagnóstico
    const LECCION_POR_NIVEL = { A1: 1, A2: 2, B1: 3, B2: 5, C1: 7 };

    // ----------------------------------------------------------
    // Utilidades
    // ----------------------------------------------------------
    function leerJSON(clave) {
        try {
            return JSON.parse(global.localStorage.getItem(clave) || "null");
        } catch (e) {
            return null;
        }
    }

    function escribirJSON(clave, valor) {
        try {
            global.localStorage.setItem(clave, JSON.stringify(valor));
        } catch (e) { /* almacenamiento lleno o bloqueado */ }
    }

    function fechaLocal(fecha = new Date()) {
        const d = new Date(fecha.getTime() - fecha.getTimezoneOffset() * 60000);
        return d.toISOString().slice(0, 10);
    }

    function sumarDias(fechaTexto, dias) {
        const d = new Date(`${fechaTexto}T12:00:00`);
        d.setDate(d.getDate() + dias);
        return fechaLocal(d);
    }

    function usuario() {
        const sesion = leerJSON(SESSION_KEY);
        if (sesion && (sesion.email || sesion.id)) {
            return {
                id: String(sesion.email || sesion.id).trim().toLowerCase(),
                nombre: sesion.nombre || "Estudiante",
                carrera: sesion.carrera || ""
            };
        }
        return { id: "invitado", nombre: "Invitado", carrera: "" };
    }

    function carreraPorSlug(slug) {
        return CATALOGO.find((c) => c.slug === slug) || null;
    }

    // "Sistemas y Software", "Medicina y Salud", "Derecho Corporativo"… → slug
    function slugDeTexto(texto) {
        const t = String(texto || "").toLowerCase().normalize("NFD").replace(/[̀-ͯ]/g, "");
        if (!t) return null;
        if (t.includes("sistema") || t.includes("software")) return "sistemas";
        if (t.includes("medic") || t.includes("salud")) return "medicina";
        if (t.includes("gastro") || t.includes("cocina")) return "gastronomia";
        if (t.includes("derecho") || t.includes("legal")) return "derecho";
        if (t.includes("arquitec")) return "arquitectura";
        if (t.includes("general")) return "general";
        return null;
    }

    // ----------------------------------------------------------
    // Progreso por carrera (misma estructura que ruta-*.js)
    // ----------------------------------------------------------
    function leerProgreso(slug) {
        const carrera = carreraPorSlug(slug);
        if (!carrera) return null;
        const todos = leerJSON(carrera.clave);
        const propio = (todos && typeof todos === "object" && !Array.isArray(todos) ? todos[usuario().id] : null) || {};
        return {
            ...propio,
            completadas: Array.isArray(propio.completadas) ? propio.completadas : [],
            personalizadas: Array.isArray(propio.personalizadas) ? propio.personalizadas : [],
            ejercicios: propio.ejercicios && typeof propio.ejercicios === "object" ? propio.ejercicios : {},
            errores: Array.isArray(propio.errores) ? propio.errores : []
        };
    }

    function guardarProgreso(slug, progreso) {
        const carrera = carreraPorSlug(slug);
        if (!carrera) return;
        const todos = leerJSON(carrera.clave);
        const datos = todos && typeof todos === "object" && !Array.isArray(todos) ? todos : {};
        datos[usuario().id] = { ...progreso, actualizado: new Date().toISOString() };
        escribirJSON(carrera.clave, datos);
        // Guardar también en la base de datos (shiftly-servidor.js)
        if (global.ShiftlyServidor) global.ShiftlyServidor.sincronizarRuta(carrera.clave);
    }

    function resumenCarrera(slug) {
        const carrera = carreraPorSlug(slug);
        if (!carrera) return null;
        const progreso = leerProgreso(slug);

        // Orden del árbol: base + personalizadas; solo cuenta el tramo completado en secuencia
        const orden = [
            ...carrera.lecciones.map((l) => ({ ...l, personalizada: false })),
            ...progreso.personalizadas.map((l, i) => ({
                id: l.id, titulo: l.titulo, xp: Number(l.xp) || 0, numero: carrera.lecciones.length + i + 1, personalizada: true
            }))
        ];
        let completadas = 0;
        let xp = 0;
        let siguiente = null;
        for (const leccion of orden) {
            if (!siguiente && progreso.completadas.includes(leccion.id)) {
                completadas++;
                xp += leccion.xp;
            } else if (!siguiente) {
                siguiente = leccion;
            }
        }

        const registros = Object.values(progreso.ejercicios);
        const xpTotal = orden.reduce((t, l) => t + l.xp, 0);
        return {
            slug,
            nombre: carrera.nombre,
            icono: carrera.icono,
            total: orden.length,
            completadas,
            xp,
            xpTotal,
            porcentaje: orden.length ? Math.round((completadas / orden.length) * 100) : 0,
            completa: orden.length > 0 && completadas === orden.length,
            iniciada: completadas > 0 || registros.length > 0,
            siguiente,
            parametroSiguiente: siguiente ? (siguiente.personalizada ? siguiente.id : String(siguiente.numero)) : null,
            lecciones: orden.map((l) => ({
                ...l,
                completada: progreso.completadas.includes(l.id),
                resultado: progreso.ejercicios[l.id] || null
            })),
            errores: progreso.errores.length,
            practicas: registros.length,
            aciertosPrimerIntento: registros.reduce((t, r) => t + (Number(r.aciertosPrimerIntento) || 0), 0),
            ejerciciosRespondidos: registros.reduce((t, r) => t + (Number(r.total) || 0), 0),
            segundos: registros.reduce((t, r) => t + (Number(r.duracionSeg) || 0), 0),
            personalizadas: progreso.personalizadas.length
        };
    }

    function resumenGlobal() {
        const porCarrera = CATALOGO.map((c) => resumenCarrera(c.slug));
        const xp = porCarrera.reduce((t, c) => t + c.xp, 0);
        const lecciones = porCarrera.reduce((t, c) => t + c.completadas, 0);
        const totalLecciones = porCarrera.reduce((t, c) => t + c.total, 0);
        const respondidos = porCarrera.reduce((t, c) => t + c.ejerciciosRespondidos, 0);
        const aciertos = porCarrera.reduce((t, c) => t + c.aciertosPrimerIntento, 0);

        let nivel = "Explorador";
        if (xp >= 600) nivel = "Experto";
        else if (xp >= 300) nivel = "Avanzado";
        else if (xp >= 100) nivel = "Aprendiz";

        return {
            xp,
            lecciones,
            totalLecciones,
            porcentaje: totalLecciones ? Math.round((lecciones / totalLecciones) * 100) : 0,
            carrerasIniciadas: porCarrera.filter((c) => c.iniciada).length,
            precision: respondidos ? Math.round((aciertos / respondidos) * 100) : null,
            segundos: porCarrera.reduce((t, c) => t + c.segundos, 0),
            errores: porCarrera.reduce((t, c) => t + c.errores, 0),
            nivel,
            porCarrera
        };
    }

    // ----------------------------------------------------------
    // Actividad diaria: racha y meta del día
    // ----------------------------------------------------------
    function leerActividad() {
        const todos = leerJSON(ACTIVIDAD_KEY);
        const propio = (todos && typeof todos === "object" ? todos[usuario().id] : null) || {};
        return {
            dias: propio.dias && typeof propio.dias === "object" ? propio.dias : {},
            meta: Number(propio.meta) || META_POR_DEFECTO,
            mejorPronunciacion: Number(propio.mejorPronunciacion) || 0
        };
    }

    function guardarActividad(actividad) {
        const todos = leerJSON(ACTIVIDAD_KEY);
        const datos = todos && typeof todos === "object" ? todos : {};
        datos[usuario().id] = actividad;
        escribirJSON(ACTIVIDAD_KEY, datos);
        // Guardar también en la base de datos (shiftly-servidor.js)
        if (global.ShiftlyServidor) global.ShiftlyServidor.sincronizarActividad();
    }

    // Llamar al completar una lección o una ronda de ejercicios
    function registrarActividad({ xp = 0 } = {}) {
        const actividad = leerActividad();
        const hoy = fechaLocal();
        const dia = actividad.dias[hoy] || { xp: 0, practicas: 0 };
        dia.xp += Math.max(0, Number(xp) || 0);
        dia.practicas += 1;
        actividad.dias[hoy] = dia;

        // Solo se conservan los últimos 120 días
        const fechas = Object.keys(actividad.dias).sort();
        fechas.slice(0, Math.max(0, fechas.length - 120)).forEach((f) => delete actividad.dias[f]);

        guardarActividad(actividad);
        return { hoy: dia, racha: racha() };
    }

    function registrarPronunciacion(porcentaje) {
        const actividad = leerActividad();
        actividad.mejorPronunciacion = Math.max(actividad.mejorPronunciacion, Math.round(Number(porcentaje) || 0));
        guardarActividad(actividad);
    }

    function racha() {
        const { dias } = leerActividad();
        const activos = new Set(Object.keys(dias).filter((f) => dias[f].practicas > 0));
        const hoy = fechaLocal();
        const practicoHoy = activos.has(hoy);

        // La racha sigue viva si practicó hoy o ayer
        let cursor = practicoHoy ? hoy : sumarDias(hoy, -1);
        let actual = 0;
        while (activos.has(cursor)) {
            actual++;
            cursor = sumarDias(cursor, -1);
        }

        let mejor = 0;
        let corrida = 0;
        let anterior = null;
        [...activos].sort().forEach((f) => {
            corrida = anterior && sumarDias(anterior, 1) === f ? corrida + 1 : 1;
            mejor = Math.max(mejor, corrida);
            anterior = f;
        });

        return { actual, mejor: Math.max(mejor, actual), practicoHoy };
    }

    function metaDiaria() {
        const actividad = leerActividad();
        const hoy = actividad.dias[fechaLocal()] || { xp: 0, practicas: 0 };
        return {
            meta: actividad.meta,
            xpHoy: hoy.xp,
            practicasHoy: hoy.practicas,
            porcentaje: Math.min(100, Math.round((hoy.xp / actividad.meta) * 100)),
            cumplida: hoy.xp >= actividad.meta
        };
    }

    function cambiarMeta(xp) {
        const actividad = leerActividad();
        actividad.meta = [10, 20, 30, 50, 100].includes(Number(xp)) ? Number(xp) : META_POR_DEFECTO;
        guardarActividad(actividad);
    }

    // Últimos 7 días para el mini calendario de racha
    function semana() {
        const { dias } = leerActividad();
        const hoy = fechaLocal();
        return Array.from({ length: 7 }, (_, i) => {
            const fecha = sumarDias(hoy, i - 6);
            const d = new Date(`${fecha}T12:00:00`);
            return {
                fecha,
                etiqueta: d.toLocaleDateString("es-MX", { weekday: "short" }).replace(".", ""),
                xp: dias[fecha] ? dias[fecha].xp : 0,
                activo: Boolean(dias[fecha] && dias[fecha].practicas > 0),
                hoy: fecha === hoy
            };
        });
    }

    // ----------------------------------------------------------
    // Insignias (se calculan con los datos existentes)
    // ----------------------------------------------------------
    function insignias() {
        const global_ = resumenGlobal();
        const r = racha();
        const { mejorPronunciacion } = leerActividad();
        const registros = global_.porCarrera.flatMap((c) => c.lecciones.map((l) => l.resultado).filter(Boolean));
        const perfectas = registros.filter((x) => x.total && x.aciertosPrimerIntento === x.total).length;

        const lista = [
            { id: "primer-paso", icono: "bi-flag-fill", nombre: "Primer paso", descripcion: "Completa tu primera lección.", obtenida: global_.lecciones >= 1 },
            { id: "racha-3", icono: "bi-fire", nombre: "En llamas", descripcion: "Practica 3 días seguidos.", obtenida: r.mejor >= 3 },
            { id: "racha-7", icono: "bi-calendar-check-fill", nombre: "Semana perfecta", descripcion: "Practica 7 días seguidos.", obtenida: r.mejor >= 7 },
            { id: "xp-100", icono: "bi-lightning-charge-fill", nombre: "100 XP", descripcion: "Acumula 100 XP.", obtenida: global_.xp >= 100 },
            { id: "xp-500", icono: "bi-gem", nombre: "500 XP", descripcion: "Acumula 500 XP.", obtenida: global_.xp >= 500 },
            { id: "ruta-completa", icono: "bi-trophy-fill", nombre: "Ruta dominada", descripcion: "Completa una ruta de carrera.", obtenida: global_.porCarrera.some((c) => c.completa) },
            { id: "multicarrera", icono: "bi-globe-americas", nombre: "Profesional global", descripcion: "Avanza en 3 carreras distintas.", obtenida: global_.carrerasIniciadas >= 3 },
            { id: "sin-errores", icono: "bi-bullseye", nombre: "Precisión total", descripcion: "Termina 3 lecciones sin fallar ningún reto.", obtenida: perfectas >= 3 },
            { id: "vidas", icono: "bi-heart-fill", nombre: "Intocable", descripcion: "Termina una lección con las 5 vidas.", obtenida: registros.some((x) => Number(x.vidas) === 5) },
            { id: "pronunciacion", icono: "bi-mic-fill", nombre: "Buena dicción", descripcion: "Logra 85% o más con el micrófono.", obtenida: mejorPronunciacion >= 85 },
            { id: "creador", icono: "bi-stars", nombre: "Creador", descripcion: "Crea una lección personalizada.", obtenida: global_.porCarrera.some((c) => c.personalizadas > 0) },
            { id: "ia", icono: "bi-robot", nombre: "Reto IA", descripcion: "Completa un reto generado con ChatGPT.", obtenida: registros.some((x) => x.fuente === "ia") }
        ];
        return lista;
    }

    // ----------------------------------------------------------
    // Diagnóstico → carrera, nivel y lección recomendada
    // ----------------------------------------------------------
    function diagnostico() {
        const resultado = leerJSON(DIAGNOSTICO_KEY);
        if (!resultado || !resultado.nivel) return null;
        const slug = slugDeTexto(resultado.carrera) || "general";
        const carrera = carreraPorSlug(slug);
        const numero = Math.min(LECCION_POR_NIVEL[resultado.nivel] || 1, carrera ? carrera.lecciones.length : 1);
        return {
            ...resultado,
            slug,
            carreraNombre: carrera ? carrera.nombre : resultado.carrera,
            leccionRecomendada: carrera ? carrera.lecciones[numero - 1] : null
        };
    }

    // Marca como completadas las lecciones anteriores a la recomendada (examen de ubicación)
    function aplicarUbicacion(slug, numero) {
        const carrera = carreraPorSlug(slug);
        if (!carrera || numero <= 1) return 0;
        const progreso = leerProgreso(slug);
        const saltadas = carrera.lecciones.slice(0, numero - 1).map((l) => l.id);
        const nuevas = saltadas.filter((id) => !progreso.completadas.includes(id));
        // Se insertan al inicio para conservar el orden secuencial del árbol
        progreso.completadas = [...saltadas, ...progreso.completadas.filter((id) => !saltadas.includes(id))];
        progreso.ubicacion = { numero, fecha: new Date().toISOString() };
        guardarProgreso(slug, progreso);
        return nuevas.length;
    }

    function carreraDelUsuario() {
        const perfil = leerJSON(PERFIL_KEY);
        const diag = diagnostico();
        return slugDeTexto(perfil && perfil.area)
            || slugDeTexto(usuario().carrera)
            || (diag ? diag.slug : null);
    }

    function formatoTiempo(segundos) {
        const s = Math.round(Number(segundos) || 0);
        if (s < 60) return `${s} s`;
        const min = Math.round(s / 60);
        return min < 60 ? `${min} min` : `${Math.floor(min / 60)} h ${min % 60} min`;
    }

    global.ShiftlyProgreso = {
        CATALOGO,
        usuario,
        carreraPorSlug,
        slugDeTexto,
        leerProgreso,
        guardarProgreso,
        resumenCarrera,
        resumenGlobal,
        registrarActividad,
        registrarPronunciacion,
        racha,
        metaDiaria,
        cambiarMeta,
        semana,
        insignias,
        diagnostico,
        aplicarUbicacion,
        carreraDelUsuario,
        formatoTiempo,
        fechaLocal
    };
})(window);
