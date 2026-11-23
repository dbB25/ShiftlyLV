/* ==========================================================
   SHIFTLY · RUTA DE GASTRONOMÍA
   Árbol de lecciones gamificado. El progreso y las lecciones
   personalizadas se guardan en la base de datos (shiftly-servidor.js);
   "shiftly_progreso_gastronomia" en localStorage es solo la copia de la página:
   { [usuario]: { completadas, personalizadas, actualizado } }
   ========================================================== */
document.addEventListener("DOMContentLoaded", () => {
    "use strict";

    // ==========================================================
    // 1. CONFIGURACIÓN DE LA CARRERA
    // ==========================================================
    const STORAGE_KEY = "shiftly_progreso_gastronomia";
    const SESSION_KEY = "shiftly_usuario_activo";
    const AREA = {
        "slug": "gastronomia",
        "nombre": "Gastronomía",
        "icono": "bi-cup-hot",
        "ejercicios": true
    };

    // Formas SVG propias de la carrera (viewBox 0 0 120 92).
    // els = silueta (se dibuja 2 veces: sombra 3D + cuerpo), det = detalles claros.
    const FORMAS = {
        "gorro": {
            "nombre": "Gorro de chef",
            "els": "<path d=\"M32 52 C10 52 8 22 30 22 C30 4 54 0 60 14 C66 0 90 4 90 22 C112 22 110 52 88 52 V78 H32 Z\"/>",
            "det": "<rect x=\"34\" y=\"62\" width=\"52\" height=\"6\" rx=\"3\"/>",
            "cx": 60,
            "cy": 33
        },
        "olla": {
            "nombre": "Olla",
            "els": "<path d=\"M14 30 H106 V62 C106 74 98 80 86 80 H34 C22 80 14 74 14 62 Z\"/><rect x=\"2\" y=\"36\" width=\"16\" height=\"10\" rx=\"5\"/><rect x=\"102\" y=\"36\" width=\"16\" height=\"10\" rx=\"5\"/><path d=\"M22 30 C22 12 98 12 98 30 Z\"/><rect x=\"54\" y=\"6\" width=\"12\" height=\"8\" rx=\"4\"/>",
            "det": "<rect x=\"24\" y=\"38\" width=\"72\" height=\"5\" rx=\"2.5\"/>",
            "cx": 60,
            "cy": 59
        },
        "sarten": {
            "nombre": "Sartén",
            "els": "<circle cx=\"46\" cy=\"44\" r=\"36\"/><rect x=\"78\" y=\"38\" width=\"40\" height=\"12\" rx=\"6\"/>",
            "det": "<circle class=\"trazo\" cx=\"46\" cy=\"44\" r=\"26\"/>",
            "cx": 46,
            "cy": 44
        },
        "cloche": {
            "nombre": "Campana de servicio (cloche)",
            "els": "<path d=\"M12 66 C12 24 108 24 108 66 Z\"/><rect x=\"4\" y=\"66\" width=\"112\" height=\"12\" rx=\"6\"/><circle cx=\"60\" cy=\"20\" r=\"8\"/>",
            "det": "<path class=\"trazo\" d=\"M28 56 C30 46 38 38 50 35\"/>",
            "cx": 60,
            "cy": 52
        },
        "plato": {
            "nombre": "Plato y cubiertos",
            "els": "<circle cx=\"60\" cy=\"44\" r=\"38\"/><rect x=\"4\" y=\"12\" width=\"8\" height=\"64\" rx=\"4\"/><rect x=\"108\" y=\"12\" width=\"8\" height=\"64\" rx=\"4\"/>",
            "det": "<circle class=\"trazo\" cx=\"60\" cy=\"44\" r=\"27\"/>",
            "cx": 60,
            "cy": 44
        }
    };

    const ICONOS = ["bi-people", "bi-basket", "bi-cup-hot", "bi-egg-fried", "bi-basket2", "bi-shield-check", "bi-egg", "bi-bell-fill", "bi-fire"];

    const LECCIONES_BASE = [
        {
            "id": "gastronomia-01",
            "titulo": "La brigada de cocina",
            "forma": "gorro",
            "icono": "bi-people",
            "xp": 10,
            "descripcion": "Conoce los puestos de la cocina profesional y quién da las órdenes.",
            "vocab": [
                {
                    "en": "head chef",
                    "es": "chef ejecutivo"
                },
                {
                    "en": "sous chef",
                    "es": "subchef"
                },
                {
                    "en": "line cook",
                    "es": "cocinero de línea"
                }
            ]
        },
        {
            "id": "gastronomia-02",
            "titulo": "Mise en place",
            "forma": "plato",
            "icono": "bi-basket",
            "xp": 15,
            "descripcion": "Organiza tu estación de trabajo antes del servicio.",
            "vocab": [
                {
                    "en": "prep list",
                    "es": "lista de preparación"
                },
                {
                    "en": "cutting board",
                    "es": "tabla de cortar"
                },
                {
                    "en": "station",
                    "es": "estación"
                }
            ]
        },
        {
            "id": "gastronomia-03",
            "titulo": "Caldos y salsas",
            "forma": "olla",
            "icono": "bi-cup-hot",
            "xp": 20,
            "descripcion": "Describe la preparación de fondos, caldos y salsas madre.",
            "vocab": [
                {
                    "en": "stock",
                    "es": "fondo / caldo"
                },
                {
                    "en": "simmer",
                    "es": "hervir a fuego lento"
                },
                {
                    "en": "reduce",
                    "es": "reducir"
                }
            ]
        },
        {
            "id": "gastronomia-04",
            "titulo": "Técnicas de cocción",
            "forma": "sarten",
            "icono": "bi-egg-fried",
            "xp": 20,
            "descripcion": "Explica técnicas de sartén y control de temperatura.",
            "vocab": [
                {
                    "en": "sauté",
                    "es": "saltear"
                },
                {
                    "en": "sear",
                    "es": "sellar"
                },
                {
                    "en": "deglaze",
                    "es": "desglasar"
                }
            ]
        },
        {
            "id": "gastronomia-05",
            "titulo": "Ingredientes y despensa",
            "forma": "plato",
            "icono": "bi-basket2",
            "xp": 25,
            "descripcion": "Haz inventario y pide ingredientes a proveedores.",
            "vocab": [
                {
                    "en": "pantry",
                    "es": "despensa"
                },
                {
                    "en": "fresh produce",
                    "es": "productos frescos"
                },
                {
                    "en": "spices",
                    "es": "especias"
                }
            ]
        },
        {
            "id": "gastronomia-06",
            "titulo": "Seguridad alimentaria",
            "forma": "olla",
            "icono": "bi-shield-check",
            "xp": 25,
            "descripcion": "Aplica normas de higiene y evita la contaminación cruzada.",
            "vocab": [
                {
                    "en": "cross-contamination",
                    "es": "contaminación cruzada"
                },
                {
                    "en": "food safety",
                    "es": "inocuidad alimentaria"
                },
                {
                    "en": "expiration date",
                    "es": "fecha de caducidad"
                }
            ]
        },
        {
            "id": "gastronomia-07",
            "titulo": "Emplatado",
            "forma": "sarten",
            "icono": "bi-egg",
            "xp": 30,
            "descripcion": "Describe la presentación de un platillo antes de servirlo.",
            "vocab": [
                {
                    "en": "plating",
                    "es": "emplatado"
                },
                {
                    "en": "garnish",
                    "es": "guarnición / decoración"
                },
                {
                    "en": "portion",
                    "es": "porción"
                }
            ]
        },
        {
            "id": "gastronomia-08",
            "titulo": "Servicio al cliente",
            "forma": "cloche",
            "icono": "bi-bell-fill",
            "xp": 50,
            "descripcion": "El platillo final: comunica pedidos entre cocina y sala.",
            "vocab": [
                {
                    "en": "order up!",
                    "es": "¡orden lista!"
                },
                {
                    "en": "table service",
                    "es": "servicio a la mesa"
                },
                {
                    "en": "the check, please",
                    "es": "la cuenta, por favor"
                }
            ]
        }
    ];

    // Patrón serpenteante (proporción del ancho del árbol)
    const PATRON_X = [0.5, 0.78, 0.5, 0.22];
    const ANCHO_NODO = 110;
    const ANCHO_ETIQUETA = 140;
    const ALTO_CONECTOR = 60;

    // Paso de receta: curva punteada con el número de paso en el centro
    function dibujarConector(x1, x2, i) {
        const mx = (x1 + x2) / 2;
        return `<path class="linea" stroke-dasharray="9 7" d="M${x1} 0 C${x1} 34, ${x2} 26, ${x2} 60"/>
                <circle class="paso" cx="${mx}" cy="30" r="11"/>
                <text class="paso-num" x="${mx}" y="34">${i + 1}</text>`;
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
