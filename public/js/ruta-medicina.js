/* ==========================================================
   SHIFTLY · RUTA DE MEDICINA
   Árbol de lecciones gamificado. El progreso y las lecciones
   personalizadas se guardan en la base de datos (shiftly-servidor.js);
   "shiftly_progreso_medicina" en localStorage es solo la copia de la página:
   { [usuario]: { completadas, personalizadas, actualizado } }
   ========================================================== */
document.addEventListener("DOMContentLoaded", () => {
    "use strict";

    // ==========================================================
    // 1. CONFIGURACIÓN DE LA CARRERA
    // ==========================================================
    const STORAGE_KEY = "shiftly_progreso_medicina";
    const SESSION_KEY = "shiftly_usuario_activo";
    const AREA = {
        "slug": "medicina",
        "nombre": "Medicina",
        "icono": "bi-heart-pulse",
        "ejercicios": true
    };

    // Formas SVG propias de la carrera (viewBox 0 0 120 92).
    // els = silueta (se dibuja 2 veces: sombra 3D + cuerpo), det = detalles claros.
    const FORMAS = {
        "cruz": {
            "nombre": "Cruz médica",
            "els": "<path d=\"M44 4 H76 V26 H104 V58 H76 V80 H44 V58 H16 V26 H44 Z\"/>",
            "det": "<rect x=\"50\" y=\"10\" width=\"20\" height=\"7\" rx=\"3.5\"/>",
            "cx": 60,
            "cy": 42
        },
        "corazon": {
            "nombre": "Corazón",
            "els": "<path d=\"M60 80 C24 58 6 40 12 22 C18 6 42 2 60 22 C78 2 102 6 108 22 C114 40 96 58 60 80 Z\"/>",
            "det": "<polyline class=\"trazo\" points=\"34,58 48,58 53,50 58,66 63,58 86,58\"/>",
            "cx": 60,
            "cy": 36
        },
        "capsula": {
            "nombre": "Cápsula",
            "els": "<rect x=\"4\" y=\"22\" width=\"112\" height=\"44\" rx=\"22\"/>",
            "det": "<path d=\"M60 22 H94 A22 22 0 0 1 94 66 H60 Z\"/><rect x=\"16\" y=\"29\" width=\"34\" height=\"6\" rx=\"3\"/>",
            "cx": 60,
            "cy": 44
        },
        "escudo": {
            "nombre": "Escudo clínico",
            "els": "<path d=\"M60 4 L106 16 V40 C106 62 86 74 60 82 C34 74 14 62 14 40 V16 Z\"/>",
            "det": "<path d=\"M60 11 L98 21 V28 L60 19 L22 28 V21 Z\"/>",
            "cx": 60,
            "cy": 45
        },
        "portapapeles": {
            "nombre": "Portapapeles",
            "els": "<rect x=\"20\" y=\"10\" width=\"80\" height=\"72\" rx=\"8\"/><rect x=\"42\" y=\"2\" width=\"36\" height=\"16\" rx=\"5\"/>",
            "det": "<rect x=\"34\" y=\"62\" width=\"52\" height=\"5\" rx=\"2.5\"/><rect x=\"34\" y=\"71\" width=\"36\" height=\"5\" rx=\"2.5\"/>",
            "cx": 60,
            "cy": 39
        }
    };

    const ICONOS = ["bi-hospital", "bi-heart-pulse", "bi-clipboard2-pulse", "bi-capsule", "bi-shield-plus", "bi-lungs", "bi-prescription2", "bi-bandaid", "bi-thermometer-half"];

    const LECCIONES_BASE = [
        {
            "id": "medicina-01",
            "titulo": "Recepción del paciente",
            "forma": "cruz",
            "icono": "bi-hospital",
            "xp": 10,
            "descripcion": "Recibe a un paciente, registra sus datos y clasifica la urgencia.",
            "vocab": [
                {
                    "en": "admission",
                    "es": "ingreso"
                },
                {
                    "en": "medical chart",
                    "es": "expediente"
                },
                {
                    "en": "triage",
                    "es": "clasificación de urgencia"
                }
            ]
        },
        {
            "id": "medicina-02",
            "titulo": "Signos vitales",
            "forma": "corazon",
            "icono": "bi-heart-pulse",
            "xp": 15,
            "descripcion": "Toma y comunica los signos vitales con precisión.",
            "vocab": [
                {
                    "en": "blood pressure",
                    "es": "presión arterial"
                },
                {
                    "en": "heart rate",
                    "es": "frecuencia cardiaca"
                },
                {
                    "en": "body temperature",
                    "es": "temperatura corporal"
                }
            ]
        },
        {
            "id": "medicina-03",
            "titulo": "Historia clínica",
            "forma": "portapapeles",
            "icono": "bi-clipboard2-pulse",
            "xp": 20,
            "descripcion": "Pregunta por antecedentes, alergias y síntomas actuales.",
            "vocab": [
                {
                    "en": "medical history",
                    "es": "antecedentes médicos"
                },
                {
                    "en": "allergy",
                    "es": "alergia"
                },
                {
                    "en": "symptoms",
                    "es": "síntomas"
                }
            ]
        },
        {
            "id": "medicina-04",
            "titulo": "Medicamentos y dosis",
            "forma": "capsula",
            "icono": "bi-capsule",
            "xp": 20,
            "descripcion": "Explica una receta, la dosis y los posibles efectos secundarios.",
            "vocab": [
                {
                    "en": "dosage",
                    "es": "dosis"
                },
                {
                    "en": "prescription",
                    "es": "receta"
                },
                {
                    "en": "side effect",
                    "es": "efecto secundario"
                }
            ]
        },
        {
            "id": "medicina-05",
            "titulo": "Bioseguridad e higiene",
            "forma": "escudo",
            "icono": "bi-shield-plus",
            "xp": 25,
            "descripcion": "Da instrucciones de protección y control de infecciones.",
            "vocab": [
                {
                    "en": "gloves",
                    "es": "guantes"
                },
                {
                    "en": "sterile",
                    "es": "estéril"
                },
                {
                    "en": "hand hygiene",
                    "es": "higiene de manos"
                }
            ]
        },
        {
            "id": "medicina-06",
            "titulo": "Sistema respiratorio",
            "forma": "corazon",
            "icono": "bi-lungs",
            "xp": 25,
            "descripcion": "Evalúa la respiración y comunica la saturación de oxígeno.",
            "vocab": [
                {
                    "en": "shortness of breath",
                    "es": "falta de aire"
                },
                {
                    "en": "oxygen saturation",
                    "es": "saturación de oxígeno"
                },
                {
                    "en": "inhale / exhale",
                    "es": "inhalar / exhalar"
                }
            ]
        },
        {
            "id": "medicina-07",
            "titulo": "Diagnóstico y tratamiento",
            "forma": "portapapeles",
            "icono": "bi-prescription2",
            "xp": 30,
            "descripcion": "Comunica un diagnóstico y acuerda un plan de tratamiento.",
            "vocab": [
                {
                    "en": "diagnosis",
                    "es": "diagnóstico"
                },
                {
                    "en": "treatment plan",
                    "es": "plan de tratamiento"
                },
                {
                    "en": "follow-up",
                    "es": "seguimiento"
                }
            ]
        },
        {
            "id": "medicina-08",
            "titulo": "Urgencias y código azul",
            "forma": "cruz",
            "icono": "bi-bandaid",
            "xp": 50,
            "descripcion": "Coordina una emergencia con el equipo médico en inglés.",
            "vocab": [
                {
                    "en": "emergency room",
                    "es": "sala de urgencias"
                },
                {
                    "en": "CPR",
                    "es": "reanimación cardiopulmonar"
                },
                {
                    "en": "stable condition",
                    "es": "condición estable"
                }
            ]
        }
    ];

    // Patrón serpenteante (proporción del ancho del árbol)
    const PATRON_X = [0.5, 0.78, 0.5, 0.22];
    const ANCHO_NODO = 110;
    const ANCHO_ETIQUETA = 140;
    const ALTO_CONECTOR = 60;

    // Línea de pulso (ECG): tramo plano, latido en el centro y llegada al siguiente nodo
    function dibujarConector(x1, x2) {
        const s = x2 >= x1 ? 1 : -1;
        const inicio = (x1 + x2) / 2 - 14 * s;
        return `<path class="linea anim" d="M${x1} 0 V30 H${inicio} l${6 * s} -4 l${4 * s} 4 l${4 * s} -22 l${5 * s} 40 l${4 * s} -24 l${5 * s} 6 H${x2} V60"/>`;
    }

    // ==========================================================
    // 2. SELECTORES DEL DOM
    // ==========================================================
    const arbol = document.getElementById("arbolRuta");
    const metaFinal = document.getElementById("metaFinal");
    const statXP = document.getElementById("statXP");
    const statLecciones = document.getElementById("statLecciones");
    const statNivel = document.getElementById("statNivel");
    const barraProgreso = document.getElementById("barraProgresoRuta");
    const textoPorcentaje = document.getElementById("textoPorcentajeRuta");
    const nombreUsuario = document.getElementById("nombreUsuarioRuta");

    const siguienteTitulo = document.getElementById("siguienteTitulo");
    const siguienteDescripcion = document.getElementById("siguienteDescripcion");
    const btnSiguiente = document.getElementById("btnAbrirSiguiente");
    const enlacePracticarSiguiente = document.getElementById("enlacePracticarSiguiente");
    const enlaceRepasarErrores = document.getElementById("enlaceRepasarErrores");
    const contadorErrores = document.getElementById("contadorErrores");
    const enlaceEjerciciosDetalle = document.getElementById("enlaceEjerciciosDetalle");
    const URL_EJERCICIOS = document.body.dataset.urlEjercicios || "/ejercicios";

    const listaPersonal = document.getElementById("listaPersonalizadas");
    const contadorPersonal = document.getElementById("contadorPersonalizadas");

    // Modal: detalle de lección
    const modalLeccionEl = document.getElementById("modalLeccion");
    const modalLeccion = new bootstrap.Modal(modalLeccionEl);
    const detalleTitulo = document.getElementById("detalleTitulo");
    const detalleIcono = document.getElementById("detalleIcono");
    const detalleForma = document.getElementById("detalleForma");
    const detalleEstado = document.getElementById("detalleEstado");
    const detalleXP = document.getElementById("detalleXP");
    const detalleDescripcion = document.getElementById("detalleDescripcion");
    const detalleVocab = document.getElementById("detalleVocab");
    const detalleAviso = document.getElementById("detalleAviso");
    const btnCompletar = document.getElementById("btnCompletarLeccion");
    const btnEditarDetalle = document.getElementById("btnEditarDesdeDetalle");
    const btnEliminarDetalle = document.getElementById("btnEliminarDesdeDetalle");

    // Modal: crear / editar lección personalizada
    const modalFormEl = document.getElementById("modalFormLeccion");
    const modalForm = new bootstrap.Modal(modalFormEl);
    const formLeccion = document.getElementById("formLeccion");
    const formTituloModal = document.getElementById("tituloModalForm");
    const inputId = document.getElementById("leccionId");
    const inputTitulo = document.getElementById("leccionTitulo");
    const inputDescripcion = document.getElementById("leccionDescripcion");
    const inputVocab = document.getElementById("leccionVocab");
    const selectXP = document.getElementById("leccionXP");
    const selectForma = document.getElementById("leccionForma");
    const selectIcono = document.getElementById("leccionIcono");
    const vistaPreviaIcono = document.getElementById("vistaPreviaIcono");
    const btnGuardarLeccion = document.getElementById("btnGuardarLeccion");

    // Modal: confirmación (eliminar / reiniciar)
    const modalConfirmarEl = document.getElementById("modalConfirmar");
    const modalConfirmar = new bootstrap.Modal(modalConfirmarEl);
    const confirmarTitulo = document.getElementById("confirmarTitulo");
    const confirmarMensaje = document.getElementById("confirmarMensaje");
    const confirmarOpcion = document.getElementById("confirmarOpcion");
    const chkBorrarPersonalizadas = document.getElementById("chkBorrarPersonalizadas");
    const btnConfirmar = document.getElementById("btnConfirmarAccion");

    // Toast de retroalimentación
    const toastEl = document.getElementById("toastRuta");
    const toast = new bootstrap.Toast(toastEl, { delay: 3200 });
    const toastIcono = document.getElementById("toastIcono");
    const toastMensaje = document.getElementById("toastMensaje");

    // ==========================================================
    // 3. UTILIDADES
    // ==========================================================
    function escaparHTML(texto) {
        return String(texto ?? "").replace(/[&<>"']/g, (c) => ({
            "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;"
        }[c]));
    }

    function leerJSON(clave) {
        try {
            return JSON.parse(localStorage.getItem(clave) || "null");
        } catch (e) {
            return null;
        }
    }

    function mostrarToast(mensaje, tipo = "success") {
        const estilos = {
            success: { icono: "bi-check-circle-fill", color: "#16A34A" },
            xp: { icono: "bi-lightning-charge-fill", color: "#FF6A2B" },
            warning: { icono: "bi-lock-fill", color: "#C9440E" },
            info: { icono: "bi-info-circle-fill", color: "#4A154B" },
            danger: { icono: "bi-trash3-fill", color: "#DC3545" }
        };
        const estilo = estilos[tipo] || estilos.info;
        toastIcono.className = `bi ${estilo.icono} fs-5`;
        toastIcono.style.color = estilo.color;
        toastMensaje.textContent = mensaje;
        toast.show();
    }

    // Cierra un modal y, cuando termina de ocultarse, abre el siguiente
    function cambiarModal(modalActual, abrirSiguiente) {
        modalActual._element.addEventListener("hidden.bs.modal", abrirSiguiente, { once: true });
        modalActual.hide();
    }

    // Voz femenina global (voz-femenina.js): OpenAI "nova" o la mejor voz del navegador
    function hablar(texto) {
        if (!window.reproducirAudioIngles || !window.VozFemenina.soportado()) {
            mostrarToast("Tu navegador no admite la lectura en voz alta.", "info");
            return;
        }
        window.reproducirAudioIngles(texto);
    }

    // ==========================================================
    // 4. USUARIO ACTIVO Y PERSISTENCIA (base de datos + copia en localStorage)
    // ==========================================================
    function obtenerUsuario() {
        const sesion = leerJSON(SESSION_KEY);
        if (sesion && (sesion.email || sesion.id)) {
            return {
                id: String(sesion.email || sesion.id).trim().toLowerCase(),
                nombre: (sesion.nombre || "Estudiante").split(" ")[0]
            };
        }
        return { id: "invitado", nombre: "Invitado" };
    }

    const usuario = obtenerUsuario();

    function estadoInicial() {
        return { completadas: [], personalizadas: [], actualizado: null };
    }

    function leerTodos() {
        const datos = leerJSON(STORAGE_KEY);
        return datos && typeof datos === "object" && !Array.isArray(datos) ? datos : {};
    }

    // READ: progreso del usuario activo
    function leerProgreso() {
        const guardado = leerTodos()[usuario.id];
        if (!guardado) return estadoInicial();
        return {
            ...guardado, // conserva datos extra (p. ej. resultados de ejercicios)
            completadas: Array.isArray(guardado.completadas) ? guardado.completadas : [],
            personalizadas: Array.isArray(guardado.personalizadas) ? guardado.personalizadas : [],
            actualizado: guardado.actualizado || null
        };
    }

    function guardarProgreso() {
        const todos = leerTodos();
        progreso.actualizado = new Date().toISOString();
        todos[usuario.id] = progreso;
        localStorage.setItem(STORAGE_KEY, JSON.stringify(todos));
        // Guardar también en la base de datos (PUT /progreso/rutas/{carrera})
        if (window.ShiftlyServidor) window.ShiftlyServidor.sincronizarRuta(STORAGE_KEY);
    }

    let progreso = leerProgreso();
    let recienCompletada = null;

    // ==========================================================
    // 5. LÓGICA DEL ÁRBOL
    // ==========================================================
    function obtenerLecciones() {
        return [
            ...LECCIONES_BASE,
            ...progreso.personalizadas.map((l) => ({ ...l, personalizada: true }))
        ];
    }

    // Desbloqueo en secuencia: completadas → 1 actual → bloqueadas
    function calcularEstados() {
        let hayActual = false;
        return obtenerLecciones().map((leccion) => {
            let estado = "bloqueado";
            if (!hayActual && progreso.completadas.includes(leccion.id)) {
                estado = "completado";
            } else if (!hayActual) {
                estado = "actual";
                hayActual = true;
            }
            return { leccion, estado };
        });
    }

    function calcularResumen(items) {
        const completadas = items.filter((i) => i.estado === "completado");
        const xp = completadas.reduce((total, i) => total + Number(i.leccion.xp || 0), 0);
        const xpTotal = items.reduce((total, i) => total + Number(i.leccion.xp || 0), 0);
        const porcentaje = items.length ? Math.round((completadas.length / items.length) * 100) : 0;
        let nivel = "Principiante";
        if (porcentaje === 100) nivel = "Dominado";
        else if (porcentaje >= 60) nivel = "Avanzado";
        else if (porcentaje >= 25) nivel = "En progreso";
        return { completadas: completadas.length, total: items.length, xp, xpTotal, porcentaje, nivel };
    }

    // Enlace a /ejercicios con la lección exacta: número 1-8 para las base, ID para las personalizadas
    function urlEjercicios(leccion) {
        if (!AREA.ejercicios || !leccion) return URL_EJERCICIOS;
        const indiceBase = LECCIONES_BASE.findIndex((l) => l.id === leccion.id);
        const parametro = indiceBase >= 0 ? String(indiceBase + 1) : leccion.id;
        return `${URL_EJERCICIOS}?carrera=${encodeURIComponent(AREA.slug)}&leccion=${encodeURIComponent(parametro)}`;
    }

    function textoEstado(estado) {
        return { completado: "Completada", actual: "En curso", bloqueado: "Bloqueada" }[estado];
    }

    function formaDe(leccion) {
        return FORMAS[leccion.forma] || FORMAS[Object.keys(FORMAS)[0]];
    }

    function dibujarNodo(item, x, ancho) {
        const { leccion, estado } = item;
        const forma = formaDe(leccion);
        const icono = estado === "bloqueado" ? "bi-lock-fill" : (ICONOS.includes(leccion.icono) ? leccion.icono : AREA.icono);
        const izquierdaEtiqueta = Math.min(Math.max(x - ANCHO_ETIQUETA / 2, 0), ancho - ANCHO_ETIQUETA);
        const clases = [
            "nodo",
            `nodo-${estado}`,
            leccion.personalizada ? "nodo-personal" : "",
            recienCompletada === leccion.id ? "nodo-recien" : ""
        ].join(" ");

        return `
            <div class="fila">
                <button type="button" class="${clases}" style="left:${x - ANCHO_NODO / 2}px"
                        data-id="${escaparHTML(leccion.id)}"
                        aria-label="${escaparHTML(leccion.titulo)} (${textoEstado(estado)})">
                    ${estado === "actual" ? `<span class="burbuja-inicio ${x >= ancho / 2 ? "izquierda" : ""}">¡Aquí!</span>` : ""}
                    <svg viewBox="0 0 120 92" aria-hidden="true">
                        <g class="sombra" transform="translate(0 8)">${forma.els}</g>
                        <g class="cuerpo">${forma.els}</g>
                        <g class="detalle">${forma.det}</g>
                    </svg>
                    <span class="nodo-icono" style="left:${(forma.cx / 120) * 100}%;top:${(forma.cy / 92) * 100}%">
                        <i class="bi ${icono}"></i>
                    </span>
                    ${estado === "completado" ? '<span class="nodo-insignia"><i class="bi bi-check-lg"></i></span>' : ""}
                </button>
                <div class="nodo-etiqueta ${estado === "bloqueado" ? "etiqueta-bloqueada" : ""}" style="left:${izquierdaEtiqueta}px">
                    ${escaparHTML(leccion.titulo)}
                    <small>+${Number(leccion.xp)} XP ${leccion.personalizada ? '<span class="pill-personal">Personal</span>' : ""}</small>
                </div>
            </div>`;
    }

    function renderArbol(items) {
        const ancho = Math.max(Math.min(arbol.clientWidth || 320, 440), 280);
        let html = "";

        items.forEach((item, i) => {
            const x = Math.round(PATRON_X[i % PATRON_X.length] * ancho);
            if (i > 0) {
                const xAnterior = Math.round(PATRON_X[(i - 1) % PATRON_X.length] * ancho);
                html += `<svg class="conector c-${item.estado}" width="${ancho}" height="${ALTO_CONECTOR}"
                              viewBox="0 0 ${ancho} ${ALTO_CONECTOR}" aria-hidden="true">
                            ${dibujarConector(xAnterior, x, i)}
                         </svg>`;
            }
            html += dibujarNodo(item, x, ancho);
        });

        arbol.innerHTML = html;
        recienCompletada = null;
    }

    function renderResumen(items, resumen) {
        statXP.textContent = `${resumen.xp} XP`;
        statLecciones.textContent = `${resumen.completadas}/${resumen.total}`;
        statNivel.textContent = resumen.nivel;
        barraProgreso.style.width = `${resumen.porcentaje}%`;
        barraProgreso.setAttribute("aria-valuenow", resumen.porcentaje);
        textoPorcentaje.textContent = `${resumen.porcentaje}% · ${resumen.xp} de ${resumen.xpTotal} XP`;

        metaFinal.classList.toggle("lograda", resumen.porcentaje === 100);

        // Repaso de errores guardados por /ejercicios
        const errores = Array.isArray(progreso.errores) ? progreso.errores.length : 0;
        enlaceRepasarErrores.classList.toggle("d-none", !AREA.ejercicios || errores === 0);
        contadorErrores.textContent = errores;
        enlaceRepasarErrores.href = `${URL_EJERCICIOS}?carrera=${encodeURIComponent(AREA.slug)}&repaso=1`;

        const actual = items.find((i) => i.estado === "actual");
        if (actual) {
            siguienteTitulo.textContent = actual.leccion.titulo;
            siguienteDescripcion.textContent = actual.leccion.descripcion || "Lección personalizada de tu ruta.";
            btnSiguiente.dataset.id = actual.leccion.id;
            btnSiguiente.disabled = false;
            btnSiguiente.innerHTML = '<i class="bi bi-play-fill me-1"></i> Abrir lección';
            enlacePracticarSiguiente.href = urlEjercicios(actual.leccion);
        } else {
            enlacePracticarSiguiente.href = urlEjercicios(items.length ? items[items.length - 1].leccion : null);
            siguienteTitulo.textContent = "¡Ruta completada!";
            siguienteDescripcion.textContent = `Dominaste todas las lecciones de ${AREA.nombre}. Agrega una lección personalizada para seguir sumando XP.`;
            btnSiguiente.dataset.id = "";
            btnSiguiente.disabled = true;
            btnSiguiente.innerHTML = '<i class="bi bi-trophy-fill me-1"></i> Completada';
        }
    }

    function renderPersonalizadas(items) {
        const personales = items.filter((i) => i.leccion.personalizada);
        contadorPersonal.textContent = personales.length;

        if (!personales.length) {
            listaPersonal.innerHTML = `
                <div class="alert alert-light border rounded-4 small mb-0">
                    <i class="bi bi-lightbulb me-1"></i>
                    Aún no tienes lecciones personalizadas. Crea una meta propia y aparecerá al final del árbol.
                </div>`;
            return;
        }

        listaPersonal.innerHTML = personales.map(({ leccion, estado }) => `
            <div class="item-personal">
                <div class="min-w-0">
                    <div class="titulo-item">${escaparHTML(leccion.titulo)}</div>
                    <span class="badge-estado ${estado}">${textoEstado(estado)}</span>
                    <span class="small texto-suave ms-1">+${Number(leccion.xp)} XP</span>
                </div>
                <div class="d-flex gap-2 flex-shrink-0">
                    <button type="button" class="btn btn-claro btn-icono" data-accion="editar" data-id="${escaparHTML(leccion.id)}" title="Editar lección">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button type="button" class="btn btn-claro btn-icono text-danger" data-accion="eliminar" data-id="${escaparHTML(leccion.id)}" title="Eliminar lección">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            </div>`).join("");
    }

    function render() {
        const items = calcularEstados();
        renderArbol(items);
        renderResumen(items, calcularResumen(items));
        renderPersonalizadas(items);
    }

    function buscarItem(id) {
        return calcularEstados().find((i) => String(i.leccion.id) === String(id));
    }

    // ==========================================================
    // 6. UPDATE: detalle y completar lección
    // ==========================================================
    let idEnModal = null;

    function abrirLeccion(id) {
        const item = buscarItem(id);
        if (!item) return;

        if (item.estado === "bloqueado") {
            const actual = calcularEstados().find((i) => i.estado === "actual");
            mostrarToast(`Lección bloqueada. Completa primero «${actual ? actual.leccion.titulo : "la lección anterior"}».`, "warning");
            return;
        }

        const { leccion, estado } = item;
        idEnModal = leccion.id;

        detalleTitulo.textContent = leccion.titulo;
        detalleIcono.className = `bi ${ICONOS.includes(leccion.icono) ? leccion.icono : AREA.icono}`;
        detalleForma.innerHTML = `<i class="bi bi-bounding-box-circles"></i> ${escaparHTML(formaDe(leccion).nombre)}`;
        detalleEstado.className = `badge-estado ${estado}`;
        detalleEstado.textContent = textoEstado(estado);
        detalleXP.textContent = `+${Number(leccion.xp)} XP`;
        detalleDescripcion.textContent = leccion.descripcion || "Lección personalizada sin descripción.";

        const vocab = Array.isArray(leccion.vocab) ? leccion.vocab : [];
        detalleVocab.innerHTML = vocab.length
            ? vocab.map((v) => `
                <div class="vocab-item">
                    <div>
                        <div class="en">${escaparHTML(v.en)}</div>
                        <div class="es">${escaparHTML(v.es)}</div>
                    </div>
                    <button type="button" class="btn btn-claro btn-icono" data-hablar="${escaparHTML(v.en)}" title="Escuchar pronunciación">
                        <i class="bi bi-volume-up-fill"></i>
                    </button>
                </div>`).join("")
            : '<p class="small texto-suave mb-0">Esta lección no tiene términos clave registrados.</p>';

        enlaceEjerciciosDetalle.href = urlEjercicios(leccion);

        const completada = estado === "completado";
        btnCompletar.classList.toggle("d-none", completada);
        detalleAviso.classList.toggle("d-none", !completada);
        btnEditarDetalle.classList.toggle("d-none", !leccion.personalizada);
        btnEliminarDetalle.classList.toggle("d-none", !leccion.personalizada);

        modalLeccion.show();
    }

    function completarLeccion() {
        const item = buscarItem(idEnModal);
        if (!item || item.estado !== "actual") return;

        progreso.completadas = [...new Set([...progreso.completadas, item.leccion.id])];
        guardarProgreso();
        // Racha diaria, meta del día e insignias (shiftly-progreso.js)
        if (window.ShiftlyProgreso) window.ShiftlyProgreso.registrarActividad({ xp: Number(item.leccion.xp) || 0 });
        recienCompletada = item.leccion.id;
        modalLeccion.hide();
        render();

        const resumen = calcularResumen(calcularEstados());
        if (resumen.porcentaje === 100) {
            mostrarToast(`¡Ruta de ${AREA.nombre} completada! Total: ${resumen.xp} XP`, "xp");
        } else {
            mostrarToast(`¡Lección completada! +${Number(item.leccion.xp)} XP. Se desbloqueó la siguiente.`, "xp");
        }
    }

    // ==========================================================
    // 7. CREATE / UPDATE: lecciones personalizadas
    // ==========================================================
    function llenarSelects() {
        selectForma.innerHTML = Object.entries(FORMAS)
            .map(([clave, forma]) => `<option value="${clave}">${escaparHTML(forma.nombre)}</option>`)
            .join("");
        selectIcono.innerHTML = ICONOS
            .map((icono) => `<option value="${icono}">${icono.replace("bi-", "").replace(/-/g, " ")}</option>`)
            .join("");
    }

    function limpiarValidacion() {
        [inputTitulo, inputDescripcion, inputVocab].forEach((campo) => campo.classList.remove("is-invalid", "is-valid"));
    }

    function abrirFormulario(leccion = null) {
        formLeccion.reset();
        limpiarValidacion();
        inputId.value = leccion ? leccion.id : "";
        formTituloModal.textContent = leccion ? "Editar lección personalizada" : "Nueva lección personalizada";
        btnGuardarLeccion.innerHTML = leccion
            ? '<i class="bi bi-save me-1"></i> Guardar cambios'
            : '<i class="bi bi-plus-circle me-1"></i> Agregar al árbol';

        if (leccion) {
            inputTitulo.value = leccion.titulo;
            inputDescripcion.value = leccion.descripcion || "";
            inputVocab.value = (leccion.vocab || []).map((v) => `${v.en} = ${v.es}`).join("\n");
            selectXP.value = String(leccion.xp);
            selectForma.value = FORMAS[leccion.forma] ? leccion.forma : Object.keys(FORMAS)[0];
            selectIcono.value = ICONOS.includes(leccion.icono) ? leccion.icono : ICONOS[0];
        }
        actualizarVistaPreviaIcono();
        modalForm.show();
    }

    function actualizarVistaPreviaIcono() {
        vistaPreviaIcono.className = `bi ${selectIcono.value || AREA.icono}`;
    }

    function convertirVocab(texto) {
        const lineas = texto.split("\n").map((l) => l.trim()).filter(Boolean);
        const vocab = [];
        for (const linea of lineas) {
            const partes = linea.split("=");
            if (partes.length !== 2 || !partes[0].trim() || !partes[1].trim()) return null;
            vocab.push({ en: partes[0].trim().slice(0, 60), es: partes[1].trim().slice(0, 60) });
        }
        return vocab.slice(0, 8);
    }

    function validarCampo(campo, esValido) {
        campo.classList.toggle("is-invalid", !esValido);
        campo.classList.toggle("is-valid", esValido);
        return esValido;
    }

    let guardandoLeccion = false;

    async function guardarLeccion(evento) {
        evento.preventDefault();
        if (guardandoLeccion) return;

        const titulo = inputTitulo.value.trim();
        const descripcion = inputDescripcion.value.trim();
        const vocab = convertirVocab(inputVocab.value);

        const tituloValido = validarCampo(inputTitulo, titulo.length >= 3 && titulo.length <= 60);
        const descripcionValida = validarCampo(inputDescripcion, descripcion.length <= 240);
        const vocabValido = validarCampo(inputVocab, vocab !== null);
        if (!tituloValido || !descripcionValida || !vocabValido) return;

        const datos = {
            titulo,
            descripcion,
            vocab,
            xp: Number(selectXP.value) || 10,
            forma: FORMAS[selectForma.value] ? selectForma.value : Object.keys(FORMAS)[0],
            icono: ICONOS.includes(selectIcono.value) ? selectIcono.value : ICONOS[0]
        };

        const idEditar = inputId.value;
        const textoBoton = btnGuardarLeccion.innerHTML;
        guardandoLeccion = true;
        btnGuardarLeccion.disabled = true;
        btnGuardarLeccion.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';

        try {
            if (!window.ShiftlyServidor) throw new Error("Inicia sesión para guardar tus lecciones.");

            if (idEditar) {
                // UPDATE: PUT /lecciones-personalizadas/{id}
                const guardada = await window.ShiftlyServidor.actualizarLeccion(idEditar, datos);
                progreso.personalizadas = progreso.personalizadas.map((l) => (l.id === idEditar ? guardada : l));
                mostrarToast("Lección personalizada actualizada.", "success");
            } else {
                // CREATE: POST /lecciones-personalizadas
                const creada = await window.ShiftlyServidor.crearLeccion(AREA.slug, datos);
                progreso.personalizadas.push(creada);
                mostrarToast("Lección agregada al final de tu árbol.", "success");
            }

            guardarProgreso();
            modalForm.hide();
            render();
        } catch (error) {
            mostrarToast(error.message || "No se pudo guardar la lección. Inténtalo de nuevo.", "warning");
        } finally {
            guardandoLeccion = false;
            btnGuardarLeccion.disabled = false;
            btnGuardarLeccion.innerHTML = textoBoton;
        }
    }

    function editarPersonalizada(id) {
        const leccion = progreso.personalizadas.find((l) => l.id === id);
        if (leccion) abrirFormulario(leccion);
    }

    // ==========================================================
    // 8. DELETE: eliminar lección / reiniciar ruta
    // ==========================================================
    let accionPendiente = null;

    function pedirConfirmacion({ titulo, mensaje, textoBoton, conOpcion = false, accion }) {
        confirmarTitulo.textContent = titulo;
        confirmarMensaje.textContent = mensaje;
        btnConfirmar.innerHTML = textoBoton;
        confirmarOpcion.classList.toggle("d-none", !conOpcion);
        chkBorrarPersonalizadas.checked = false;
        accionPendiente = accion;
        modalConfirmar.show();
    }

    function eliminarPersonalizada(id) {
        const leccion = progreso.personalizadas.find((l) => l.id === id);
        if (!leccion) return;

        pedirConfirmacion({
            titulo: "Eliminar lección",
            mensaje: `¿Seguro que deseas eliminar «${leccion.titulo}»? Se descontará su XP si ya estaba completada.`,
            textoBoton: '<i class="bi bi-trash3 me-1"></i> Eliminar',
            accion: async () => {
                try {
                    // DELETE: DELETE /lecciones-personalizadas/{id}
                    if (window.ShiftlyServidor) await window.ShiftlyServidor.eliminarLeccion(id);
                } catch (error) {
                    mostrarToast(error.message || "No se pudo eliminar la lección. Inténtalo de nuevo.", "warning");
                    return;
                }
                progreso.personalizadas = progreso.personalizadas.filter((l) => l.id !== id);
                progreso.completadas = progreso.completadas.filter((c) => c !== id);
                guardarProgreso();
                render();
                mostrarToast("Lección personalizada eliminada.", "danger");
            }
        });
    }

    function reiniciarRuta() {
        pedirConfirmacion({
            titulo: "Reiniciar progreso de ruta",
            mensaje: `Se borrarán tus lecciones completadas y el XP de ${AREA.nombre}. Esta acción no se puede deshacer.`,
            textoBoton: '<i class="bi bi-arrow-counterclockwise me-1"></i> Reiniciar',
            conOpcion: true,
            accion: async () => {
                const borrarPersonalizadas = chkBorrarPersonalizadas.checked;
                if (borrarPersonalizadas && window.ShiftlyServidor) {
                    try {
                        for (const leccion of progreso.personalizadas) {
                            await window.ShiftlyServidor.eliminarLeccion(leccion.id);
                        }
                    } catch (error) {
                        mostrarToast(error.message || "No se pudieron eliminar tus lecciones. Inténtalo de nuevo.", "warning");
                        return;
                    }
                }
                progreso = {
                    ...estadoInicial(),
                    personalizadas: borrarPersonalizadas ? [] : progreso.personalizadas
                };
                guardarProgreso();
                render();
                mostrarToast("Ruta reiniciada. ¡A empezar de nuevo!", "info");
            }
        });
    }

    // ==========================================================
    // 9. EVENTOS
    // ==========================================================
    arbol.addEventListener("click", (evento) => {
        const nodo = evento.target.closest(".nodo");
        if (nodo) abrirLeccion(nodo.dataset.id);
    });

    btnSiguiente.addEventListener("click", () => {
        if (btnSiguiente.dataset.id) abrirLeccion(btnSiguiente.dataset.id);
    });

    btnCompletar.addEventListener("click", completarLeccion);

    btnEditarDetalle.addEventListener("click", () => {
        const id = idEnModal;
        cambiarModal(modalLeccion, () => editarPersonalizada(id));
    });

    btnEliminarDetalle.addEventListener("click", () => {
        const id = idEnModal;
        cambiarModal(modalLeccion, () => eliminarPersonalizada(id));
    });

    detalleVocab.addEventListener("click", (evento) => {
        const boton = evento.target.closest("[data-hablar]");
        if (boton) hablar(boton.dataset.hablar);
    });

    listaPersonal.addEventListener("click", (evento) => {
        const boton = evento.target.closest("[data-accion]");
        if (!boton) return;
        if (boton.dataset.accion === "editar") editarPersonalizada(boton.dataset.id);
        if (boton.dataset.accion === "eliminar") eliminarPersonalizada(boton.dataset.id);
    });

    document.querySelectorAll('[data-accion="agregar-leccion"]').forEach((boton) =>
        boton.addEventListener("click", () => abrirFormulario())
    );

    document.querySelectorAll('[data-accion="reiniciar-ruta"]').forEach((boton) =>
        boton.addEventListener("click", reiniciarRuta)
    );

    btnConfirmar.addEventListener("click", () => {
        const accion = accionPendiente;
        accionPendiente = null;
        modalConfirmar.hide();
        if (typeof accion === "function") accion();
    });

    formLeccion.addEventListener("submit", guardarLeccion);
    inputTitulo.addEventListener("input", () => inputTitulo.classList.remove("is-invalid"));
    inputVocab.addEventListener("input", () => inputVocab.classList.remove("is-invalid"));
    selectIcono.addEventListener("change", actualizarVistaPreviaIcono);

    // Redibuja el serpenteo al cambiar el tamaño de pantalla
    let temporizadorResize = null;
    window.addEventListener("resize", () => {
        clearTimeout(temporizadorResize);
        temporizadorResize = setTimeout(render, 150);
    });

    // Sincroniza si el progreso cambia en otra pestaña
    window.addEventListener("storage", (evento) => {
        if (evento.key === SESSION_KEY) {
            window.location.reload();
        } else if (evento.key === STORAGE_KEY) {
            progreso = leerProgreso();
            render();
        }
    });

    // ==========================================================
    // 10. INICIO
    // ==========================================================
    nombreUsuario.textContent = usuario.nombre;
    llenarSelects();
    render();
});
