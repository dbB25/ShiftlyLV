/* ==========================================================
   SHIFTLY · PROGRESO EN LA BASE DE DATOS (window.ShiftlyServidor)

   La base de datos (PostgreSQL) es la fuente de verdad del progreso:
   1. Al cargar la página, el servidor entrega el progreso del usuario
      (window.SHIFTLY_SERVIDOR, desde partials/progreso-servidor.blade.php)
      y este script lo copia a las claves que leen ruta-*.js,
      ejercicios-leccion.js y shiftly-progreso.js. El navegador solo
      guarda una copia temporal del usuario que inició sesión.
   2. Cada vez que esos scripts guardan un cambio, llaman a
      ShiftlyServidor.sincronizarRuta() / sincronizarActividad() y el
      cambio se envía a la API de Laravel (/progreso/...).
   3. Las lecciones personalizadas usan su propio CRUD
      (/lecciones-personalizadas): crear, editar y eliminar.

   Si un envío no llega (sin internet, se cerró la página), el cambio
   queda marcado como pendiente y se reenvía en la siguiente carga.
   ========================================================== */
(function (global) {
    "use strict";

    const config = global.SHIFTLY_SERVIDOR;
    if (!config) return;

    const SESSION_KEY = "shiftly_usuario_activo";
    const ACTIVIDAD_KEY = "shiftly_actividad";
    const DIAGNOSTICO_KEY = "shiftlyDiagnosticoResultado";
    const PENDIENTES_KEY = "shiftly_pendientes";
    const PREFIJO_RUTA = "shiftly_progreso_";
    const LIMITE_KEEPALIVE = 60000; // bytes que el navegador permite enviar al cerrar la página

    const usuarioId = String(config.usuario.email || "").trim().toLowerCase();

    // ----------------------------------------------------------
    // Utilidades de almacenamiento
    // ----------------------------------------------------------
    function leer(clave) {
        try {
            return JSON.parse(global.localStorage.getItem(clave) || "null");
        } catch (e) {
            return null;
        }
    }

    function escribir(clave, valor) {
        try {
            global.localStorage.setItem(clave, JSON.stringify(valor));
        } catch (e) { /* almacenamiento bloqueado: los scripts usarán datos vacíos */ }
    }

    function entradaDelUsuario(clave) {
        const todos = leer(clave);
        return todos && typeof todos === "object" && !Array.isArray(todos) ? todos[usuarioId] || null : null;
    }

    // Pendientes: { clave: marca } de cambios que aún no confirma el servidor
    function marcarPendiente(clave) {
        const pendientes = leer(PENDIENTES_KEY) || {};
        const marca = `${Date.now()}-${Math.random().toString(36).slice(2, 8)}`;
        pendientes[clave] = marca;
        escribir(PENDIENTES_KEY, pendientes);
        return marca;
    }

    function limpiarPendiente(clave, marca) {
        const pendientes = leer(PENDIENTES_KEY) || {};
        if (pendientes[clave] !== marca) return; // hubo un cambio más nuevo
        delete pendientes[clave];
        escribir(PENDIENTES_KEY, pendientes);
    }

    // ----------------------------------------------------------
    // 1. Copiar el progreso del servidor al navegador
    // ----------------------------------------------------------
    const sesion = {
        id: usuarioId,
        email: usuarioId,
        nombre: config.usuario.nombre || "Estudiante",
        carrera: config.usuario.carrera || ""
    };
    // Solo se escribe si cambió: las otras pestañas recargan al ver un cambio de sesión
    if (JSON.stringify(leer(SESSION_KEY)) !== JSON.stringify(sesion)) {
        escribir(SESSION_KEY, sesion);
    }

    const pendientes = leer(PENDIENTES_KEY) || {};
    const porReenviar = [];

    Object.entries(config.progreso.rutas || {}).forEach(([carrera, delServidor]) => {
        const clave = PREFIJO_RUTA + carrera;
        const local = entradaDelUsuario(clave);
        let propio = delServidor;

        // Un cambio que no alcanzó a llegar al servidor gana sobre la copia del servidor
        if (pendientes[clave] && local) {
            propio = { ...local, personalizadas: delServidor.personalizadas };
            porReenviar.push(() => sincronizarRuta(clave));
        } else {
            delete pendientes[clave];
        }
        // Solo queda el usuario que inició sesión (nada de otros usuarios de este navegador)
        escribir(clave, { [usuarioId]: propio });
    });

    const actividadLocal = entradaDelUsuario(ACTIVIDAD_KEY);
    if (pendientes[ACTIVIDAD_KEY] && actividadLocal) {
        porReenviar.push(() => sincronizarActividad());
    } else {
        delete pendientes[ACTIVIDAD_KEY];
        escribir(ACTIVIDAD_KEY, { [usuarioId]: config.progreso.actividad });
    }
    // Diagnóstico: el último resultado guardado en la base de datos
    const diagnosticoLocal = leer(DIAGNOSTICO_KEY);
    if (pendientes[DIAGNOSTICO_KEY] && diagnosticoLocal) {
        porReenviar.push(() => guardarDiagnostico(diagnosticoLocal));
    } else {
        delete pendientes[DIAGNOSTICO_KEY];
        try {
            if (config.progreso.diagnostico) escribir(DIAGNOSTICO_KEY, config.progreso.diagnostico);
            else global.localStorage.removeItem(DIAGNOSTICO_KEY);
        } catch (e) { /* almacenamiento bloqueado */ }
    }

    escribir(PENDIENTES_KEY, pendientes);

    // ----------------------------------------------------------
    // 2. Peticiones a la API de Laravel
    // ----------------------------------------------------------
    async function pedir(metodo, url, datos) {
        const cuerpo = datos === undefined ? undefined : JSON.stringify(datos);
        const respuesta = await fetch(url, {
            method: metodo,
            credentials: "same-origin",
            keepalive: Boolean(cuerpo) && cuerpo.length < LIMITE_KEEPALIVE,
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": config.csrf,
                "X-Requested-With": "XMLHttpRequest"
            },
            body: cuerpo
        });

        let json = null;
        try { json = await respuesta.json(); } catch (e) { /* respuesta sin cuerpo */ }

        if (!respuesta.ok) {
            const errores = json && json.errors ? Object.values(json.errors).flat() : [];
            const error = new Error(errores[0] || (json && json.message) || `Error ${respuesta.status} al guardar.`);
            error.status = respuesta.status;
            throw error;
        }
        return json;
    }

    // Un envío a la vez por clave, para que el servidor nunca reciba un estado viejo después de uno nuevo
    const cola = {};

    function encolar(clave, enviar) {
        const marca = marcarPendiente(clave);
        const estado = cola[clave] || (cola[clave] = { enviando: false, otraVez: false });
        if (estado.enviando) {
            estado.otraVez = true;
            return;
        }

        estado.enviando = true;
        enviar()
            .then(() => limpiarPendiente(clave, marca))
            .catch((error) => console.warn(`Shiftly: no se pudo guardar el progreso (${clave}). Se reintentará.`, error))
            .finally(() => {
                estado.enviando = false;
                if (estado.otraVez) {
                    estado.otraVez = false;
                    encolar(clave, enviar);
                }
            });
    }

    // XP de cada lección completada (catálogo + personalizadas) para guardarlo en la base
    function xpDeLecciones(carrera, progreso) {
        const xp = {};
        const catalogo = (global.SHIFTLY_CATALOGO || []).find((c) => c.slug === carrera);
        const lecciones = [...(catalogo ? catalogo.lecciones : []), ...(progreso.personalizadas || [])];
        (progreso.completadas || []).forEach((id) => {
            const leccion = lecciones.find((l) => l.id === id);
            xp[id] = leccion ? Number(leccion.xp) || 0 : 0;
        });
        return xp;
    }

    function sincronizarRuta(clave) {
        const carrera = String(clave).replace(PREFIJO_RUTA, "");
        encolar(clave, () => {
            const progreso = entradaDelUsuario(clave) || {};
            const ejercicios = progreso.ejercicios && typeof progreso.ejercicios === "object" ? progreso.ejercicios : {};
            return pedir("PUT", `${config.urls.rutas}/${encodeURIComponent(carrera)}`, {
                completadas: Array.isArray(progreso.completadas) ? progreso.completadas : [],
                xp: xpDeLecciones(carrera, progreso),
                ejercicios,
                errores: Array.isArray(progreso.errores) ? progreso.errores : [],
                ubicacion: progreso.ubicacion || null
            });
        });
    }

    function sincronizarActividad() {
        encolar(ACTIVIDAD_KEY, () => {
            const actividad = entradaDelUsuario(ACTIVIDAD_KEY) || {};
            return pedir("PUT", config.urls.actividad, {
                dias: actividad.dias || {},
                meta: actividad.meta || 30,
                mejorPronunciacion: actividad.mejorPronunciacion || 0
            });
        });
    }

    // Práctica libre de /ejercicios: se guarda poco después del último cambio
    let temporizadorPractica = null;
    function guardarPractica(datos) {
        clearTimeout(temporizadorPractica);
        temporizadorPractica = setTimeout(() => {
            pedir("PUT", config.urls.practica, datos)
                .catch((error) => console.warn("Shiftly: no se pudo guardar la práctica.", error));
        }, 600);
    }

    // Examen diagnóstico: cada intento queda en el historial del alumno
    function guardarDiagnostico(resultado) {
        encolar(DIAGNOSTICO_KEY, () => pedir("POST", config.urls.diagnostico, { resultado }));
    }

    // ----------------------------------------------------------
    // 3. CRUD de lecciones personalizadas
    // ----------------------------------------------------------
    function idNumerico(idArbol) {
        const coincidencia = /^p-(\d+)$/.exec(String(idArbol));
        return coincidencia ? coincidencia[1] : null;
    }

    async function crearLeccion(carrera, datos) {
        const json = await pedir("POST", config.urls.lecciones, { carrera, ...datos });
        return json.leccion;
    }

    async function actualizarLeccion(idArbol, datos) {
        const id = idNumerico(idArbol);
        if (!id) throw new Error("Esta lección no existe en la base de datos.");
        const json = await pedir("PUT", `${config.urls.lecciones}/${id}`, datos);
        return json.leccion;
    }

    async function eliminarLeccion(idArbol) {
        const id = idNumerico(idArbol);
        if (!id) return; // lección vieja que solo existía en este navegador
        try {
            await pedir("DELETE", `${config.urls.lecciones}/${id}`);
        } catch (error) {
            if (error.status !== 404) throw error; // ya estaba eliminada
        }
    }

    // Reenviar lo pendiente cuando ya cargaron los demás scripts (catálogo de XP)
    global.addEventListener("DOMContentLoaded", () => porReenviar.forEach((reenviar) => reenviar()));
    global.addEventListener("online", () => {
        const aun = leer(PENDIENTES_KEY) || {};
        Object.keys(aun).forEach((clave) => {
            if (clave === ACTIVIDAD_KEY) sincronizarActividad();
            else if (clave.startsWith(PREFIJO_RUTA)) sincronizarRuta(clave);
        });
    });

    global.ShiftlyServidor = {
        usuarioId,
        sincronizarRuta,
        sincronizarActividad,
        guardarPractica,
        guardarDiagnostico,
        crearLeccion,
        actualizarLeccion,
        eliminarLeccion
    };
})(window);
