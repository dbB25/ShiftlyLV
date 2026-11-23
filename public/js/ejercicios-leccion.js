/* ==========================================================
   SHIFTLY · MODO LECCIÓN DE EJERCICIOS
   /ejercicios?carrera=sistemas&leccion=3  → ejercicios de esa lección
   /ejercicios?carrera=sistemas&leccion=p-… → set general (lección personal)

   - Banco local: window.SHIFTLY_BANCOS[carrera] (ejercicios-<carrera>.js)
   - Modo híbrido con ChatGPT / OpenAI (openai-ejercicios.js → window.ServicioIA)
   - Audio con speechSynthesis y micrófono con SpeechRecognition
   - Al terminar guarda la lección como completada en la base de datos
     (shiftly-servidor.js → PUT /progreso/rutas/{carrera}) y en la copia
     del navegador localStorage[banco.claveProgreso][usuario]

   Sin parámetros, la vista conserva su práctica original (ejercicios.js).
   ========================================================== */
(function () {
    "use strict";

    const params = new URLSearchParams(window.location.search);
    const CARRERA = (params.get("carrera") || "").trim().toLowerCase();
    const LECCION_PARAM = (params.get("leccion") || "").trim();
    // ?carrera=…&repaso=1 → practica los ejercicios que el alumno falló antes
    const MODO_REPASO = params.get("repaso") === "1";
    const MAX_ERRORES = 40;

    // Bandera leída por ejercicios.js y el script de sincronización de la vista
    window.SHIFTLY_MODO_LECCION = Boolean(CARRERA && (LECCION_PARAM || MODO_REPASO));
    if (!window.SHIFTLY_MODO_LECCION) return;

    const SESSION_KEY = "shiftly_usuario_activo";
    const PERFIL_KEY = "shiftly_user_profile_full";
    const TOTAL_VIDAS = 5;

    const TIPOS = {
        opcion: { nombre: "Opción múltiple", icono: "🎯" },
        completar: { nombre: "Completa la frase", icono: "✍️" },
        ordenar: { nombre: "Ordena las palabras", icono: "🧩" },
        emparejar: { nombre: "Empareja los pares", icono: "🔗" },
        escuchar: { nombre: "Comprensión auditiva", icono: "🎧" }
    };

    // ==========================================================
    // UTILIDADES
    // ==========================================================
    const $ = (id) => document.getElementById(id);

    function escaparHTML(valor) {
        return String(valor ?? "").replace(/[&<>"']/g, (c) => ({
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

    function barajar(lista) {
        const copia = [...lista];
        for (let i = copia.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [copia[i], copia[j]] = [copia[j], copia[i]];
        }
        return copia;
    }

    function normalizarTexto(valor) {
        return String(valor || "")
            .toLowerCase()
            .normalize("NFD").replace(/[̀-ͯ]/g, "")
            .replace(/[’`]/g, "'")
            .replace(/[.,!?;:"]/g, "")
            .replace(/\s+/g, " ")
            .trim();
    }

    // Similitud 0-1 basada en distancia de Levenshtein (para pronunciación)
    function similitud(a, b) {
        a = normalizarTexto(a);
        b = normalizarTexto(b);
        if (!a.length && !b.length) return 1;
        const fila = Array.from({ length: b.length + 1 }, (_, i) => i);
        for (let i = 1; i <= a.length; i++) {
            let anterior = fila[0];
            fila[0] = i;
            for (let j = 1; j <= b.length; j++) {
                const temporal = fila[j];
                fila[j] = Math.min(fila[j] + 1, fila[j - 1] + 1, anterior + (a[i - 1] === b[j - 1] ? 0 : 1));
                anterior = temporal;
            }
        }
        return 1 - fila[b.length] / Math.max(a.length, b.length);
    }

    // ==========================================================
    // AUDIO: voz femenina global (voz-femenina.js)
    // OpenAI TTS "nova" si hay API Key; si no, la mejor voz del navegador
    // ==========================================================
    function hablar(texto, lang = "en-US", velocidad) {
        if (!texto || !window.reproducirAudioIngles) return;
        window.reproducirAudioIngles(texto, { lang, velocidad });
    }

    // ==========================================================
    // MICRÓFONO (Web Speech API: SpeechRecognition)
    // ==========================================================
    const Reconocimiento = window.SpeechRecognition || window.webkitSpeechRecognition;
    const MICROFONO_DISPONIBLE = typeof Reconocimiento === "function";
    let reconocimientoActivo = null;

    function escucharPronunciacion(objetivo, boton, salida) {
        if (!MICROFONO_DISPONIBLE) return;
        if (reconocimientoActivo) {
            reconocimientoActivo.abort();
            return;
        }

        const rec = new Reconocimiento();
        rec.lang = "en-US";
        rec.interimResults = false;
        rec.maxAlternatives = 3;
        reconocimientoActivo = rec;

        boton.classList.add("escuchando");
        salida.className = "resultado-voz info";
        salida.innerHTML = '<i class="bi bi-mic-fill me-1"></i> Escuchando… di la frase en inglés.';

        rec.onresult = (evento) => {
            const alternativas = Array.from(evento.results[0]).map((r) => r.transcript);
            const mejor = alternativas
                .map((t) => ({ t, p: similitud(t, objetivo) }))
                .sort((x, y) => y.p - x.p)[0];
            const porcentaje = Math.round(mejor.p * 100);
            let clase = "error";
            let mensaje = "Intenta de nuevo, escucha el audio primero.";
            if (porcentaje >= 85) { clase = "ok"; mensaje = "¡Excelente pronunciación!"; }
            else if (porcentaje >= 60) { clase = "medio"; mensaje = "¡Casi! Repite con calma."; }
            salida.className = `resultado-voz ${clase}`;
            salida.innerHTML = `<strong>${porcentaje}%</strong> · ${mensaje}<br><span class="small">Escuché: «${escaparHTML(mejor.t)}»</span>`;
            // Para la insignia "Buena dicción"
            if (window.ShiftlyProgreso) window.ShiftlyProgreso.registrarPronunciacion(porcentaje);
        };

        rec.onerror = (evento) => {
            const mensajes = {
                "not-allowed": "Permite el uso del micrófono para practicar.",
                "no-speech": "No se detectó voz. Intenta de nuevo.",
                "network": "El reconocimiento de voz necesita conexión a internet."
            };
            if (evento.error === "aborted") return;
            salida.className = "resultado-voz error";
            salida.textContent = mensajes[evento.error] || "No se pudo usar el micrófono.";
        };

        rec.onend = () => {
            boton.classList.remove("escuchando");
            reconocimientoActivo = null;
            if (salida.classList.contains("info")) {
                salida.className = "resultado-voz d-none";
            }
        };

        rec.start();
    }

    // ==========================================================
    // USUARIO Y PROGRESO (misma estructura que ruta-*.js)
    // ==========================================================
    function obtenerUsuarioId() {
        const sesion = leerJSON(SESSION_KEY);
        if (sesion && (sesion.email || sesion.id)) {
            return String(sesion.email || sesion.id).trim().toLowerCase();
        }
        return "invitado";
    }

    function obtenerNivel() {
        const perfil = leerJSON(PERFIL_KEY);
        return (perfil && perfil.nivel) || "B1 Intermedio";
    }

    function leerProgresoUsuario(banco) {
        const todos = leerJSON(banco.claveProgreso);
        const datos = todos && typeof todos === "object" && !Array.isArray(todos) ? todos : {};
        const propio = datos[obtenerUsuarioId()] || {};
        return {
            todos: datos,
            progreso: {
                ...propio,
                completadas: Array.isArray(propio.completadas) ? propio.completadas : [],
                personalizadas: Array.isArray(propio.personalizadas) ? propio.personalizadas : []
            }
        };
    }

    // Orden del árbol: lecciones base + personalizadas (igual que la ruta)
    function ordenDeLecciones(banco, progreso) {
        return [
            ...banco.lecciones.map((l) => ({ id: l.id, titulo: l.titulo, numero: l.numero })),
            ...progreso.personalizadas.map((l) => ({ id: l.id, titulo: l.titulo }))
        ];
    }

    function leccionActual(orden, completadas) {
        return orden.find((l) => !completadas.includes(l.id)) || null;
    }

    // Guarda el resultado. Devuelve el estado para el resumen final.
    function guardarResultado(banco, leccion, resumen) {
        const { todos, progreso } = leerProgresoUsuario(banco);
        const orden = ordenDeLecciones(banco, progreso);
        const actualAntes = leccionActual(orden, progreso.completadas);

        let estado = "repaso";
        if (!progreso.completadas.includes(leccion.id)) {
            estado = actualAntes && actualAntes.id === leccion.id ? "nueva" : "bloqueada";
        }
        if (estado === "nueva") {
            progreso.completadas = [...progreso.completadas, leccion.id];
        }

        progreso.ejercicios = {
            ...(progreso.ejercicios || {}),
            [leccion.id]: {
                aciertosPrimerIntento: resumen.aciertosPrimerIntento,
                total: resumen.total,
                vidas: resumen.vidas,
                fuente: resumen.fuente,
                duracionSeg: resumen.duracionSeg,
                fecha: new Date().toISOString()
            }
        };
        progreso.actualizado = new Date().toISOString();
        todos[obtenerUsuarioId()] = progreso;
        localStorage.setItem(banco.claveProgreso, JSON.stringify(todos));
        // Guardar también en la base de datos (shiftly-servidor.js)
        if (window.ShiftlyServidor) window.ShiftlyServidor.sincronizarRuta(banco.claveProgreso);

        return {
            estado,
            actualAntes,
            siguiente: leccionActual(orden, progreso.completadas)
        };
    }

    // ==========================================================
    // ERRORES PARA REPASO (progreso.errores en la misma clave de la ruta)
    // ==========================================================
    function claveEjercicio(leccionId, ej) {
        const firma = ej.frase || ej.antes || ej.audio
            || (ej.palabras || []).join(" ")
            || (ej.pares || []).map((p) => p.en).join("|");
        return `${leccionId}|${ej.tipo}|${firma}`;
    }

    function modificarProgreso(banco, cambio) {
        const { todos, progreso } = leerProgresoUsuario(banco);
        progreso.errores = Array.isArray(progreso.errores) ? progreso.errores : [];
        cambio(progreso);
        progreso.actualizado = new Date().toISOString();
        todos[obtenerUsuarioId()] = progreso;
        localStorage.setItem(banco.claveProgreso, JSON.stringify(todos));
        // Guardar también en la base de datos (shiftly-servidor.js)
        if (window.ShiftlyServidor) window.ShiftlyServidor.sincronizarRuta(banco.claveProgreso);
        return progreso;
    }

    function registrarError(banco, leccion, ej) {
        const clave = claveEjercicio(leccion.id, ej);
        modificarProgreso(banco, (progreso) => {
            if (progreso.errores.some((e) => e.clave === clave)) return;
            progreso.errores.push({
                clave,
                leccionId: leccion.id,
                leccionTitulo: leccion.titulo,
                ejercicio: ej,
                fecha: new Date().toISOString()
            });
            // Se conservan los más recientes
            progreso.errores = progreso.errores.slice(-MAX_ERRORES);
        });
    }

    function quitarErrores(banco, claves) {
        if (!claves.length) return modificarProgreso(banco, () => {}).errores.length;
        return modificarProgreso(banco, (progreso) => {
            progreso.errores = progreso.errores.filter((e) => !claves.includes(e.clave));
        }).errores.length;
    }

    function registrarActividadDiaria(xp) {
        if (window.ShiftlyProgreso) window.ShiftlyProgreso.registrarActividad({ xp });
    }

    // ==========================================================
    // ARRANQUE
    // ==========================================================
    document.addEventListener("DOMContentLoaded", iniciar);

    function iniciar() {
        const ui = {
            badge: $("badgeLeccion"),
            titulo: $("tituloLeccion"),
            descripcion: $("descripcionLeccion"),
            vidas: $("heartsCount"),
            xp: $("xpCount"),
            racha: $("streakCount"),
            barra: $("mainProgressBar"),
            textoProgreso: document.querySelector(".progress-copy"),
            tipoIcono: $("typeIcon"),
            tipoNombre: $("typeName"),
            contador: $("counter"),
            consejo: $("tip"),
            palabrasClave: $("palabrasClave"),
            badgePalabras: $("badgePalabras"),
            contenedor: $("practicaLeccion"),
            urlRuta: document.body.dataset.urlRuta || "/ruta-",
            urlEjercicios: document.body.dataset.urlEjercicios || "/ejercicios"
        };

        // Ocultar la práctica estática original
        document.querySelectorAll("#exercises > .exercise").forEach((el) => { el.hidden = true; });
        $("lessonSummary").classList.add("d-none");
        $("lessonFooterNav").classList.add("d-none");
        ui.contenedor.classList.remove("d-none");

        const banco = (window.SHIFTLY_BANCOS || {})[CARRERA];
        if (!banco) {
            mostrarError(ui, "Esta carrera todavía no tiene ejercicios conectados.", null);
            return;
        }

        const urlRuta = `${ui.urlRuta}${banco.slug}`;
        const leccion = MODO_REPASO ? leccionDeRepaso(banco) : resolverLeccion(banco);
        if (MODO_REPASO && !leccion) {
            mostrarError(ui, "¡No tienes errores pendientes en esta ruta! Sigue practicando lecciones nuevas.", urlRuta);
            ui.badge.innerHTML = '<i class="bi bi-check2-circle"></i> Repaso al día';
            ui.titulo.textContent = "Sin errores por repasar";
            return;
        }
        if (!leccion) {
            mostrarError(ui, "No encontramos esa lección en tu ruta. Puede que la lección personalizada se haya eliminado.", urlRuta);
            return;
        }

        new SesionLeccion(ui, banco, leccion, urlRuta).arrancar();
    }

    // Sesión de repaso: hasta 5 ejercicios fallados, los más antiguos primero
    function leccionDeRepaso(banco) {
        const { progreso } = leerProgresoUsuario(banco);
        const errores = Array.isArray(progreso.errores) ? progreso.errores : [];
        if (!errores.length) return null;
        const seleccion = errores.slice(0, 5);
        const terminos = seleccion
            .flatMap((e) => (e.ejercicio.tipo === "emparejar" ? e.ejercicio.pares : []))
            .slice(0, 6);
        return {
            id: "repaso",
            numero: 0,
            titulo: "Repaso de mis errores",
            xp: 0,
            consejo: `Tienes ${errores.length} ejercicio${errores.length === 1 ? "" : "s"} por repasar. Si aciertas a la primera, sale de tu lista.`,
            terminos: terminos.length ? terminos : terminosDe(banco.general),
            ejercicios: seleccion.map((e) => e.ejercicio),
            clavesRepaso: seleccion.map((e) => e.clave),
            totalErrores: errores.length,
            personalizada: false,
            repaso: true,
            etiqueta: "Repaso"
        };
    }

    // Lección base por número (1-8) o ID; si no, lección personalizada del usuario
    function resolverLeccion(banco) {
        const numero = Number(LECCION_PARAM);
        const base = banco.lecciones.find((l) =>
            (Number.isInteger(numero) && l.numero === numero) || l.id === LECCION_PARAM
        );
        if (base) {
            return {
                ...base,
                terminos: terminosDe(base),
                personalizada: false,
                etiqueta: `Lección ${base.numero}`
            };
        }

        const { progreso } = leerProgresoUsuario(banco);
        const indice = progreso.personalizadas.findIndex((l) => l.id === LECCION_PARAM);
        if (indice === -1) return null;
        const propia = progreso.personalizadas[indice];
        const terminos = Array.isArray(propia.vocab) && propia.vocab.length ? propia.vocab : terminosDe(banco.general);

        return {
            id: propia.id,
            numero: banco.lecciones.length + indice + 1,
            titulo: propia.titulo || "Lección personalizada",
            xp: Number(propia.xp) || 10,
            temas: `${propia.titulo}. ${propia.descripcion || ""} ${banco.general.temas}`.trim(),
            consejo: banco.general.consejo,
            terminos,
            ejercicios: banco.general.ejercicios,
            personalizada: true,
            etiqueta: "Lección personal"
        };
    }

    // Términos del panel lateral: los definidos en el banco o, si no hay,
    // los pares del ejercicio de emparejar de esa lección
    function terminosDe(fuente) {
        if (Array.isArray(fuente.terminos) && fuente.terminos.length) return fuente.terminos;
        const emparejar = (fuente.ejercicios || []).find((e) => e.tipo === "emparejar");
        return emparejar ? emparejar.pares : [];
    }

    function mostrarError(ui, mensaje, urlRuta) {
        ui.badge.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Lección no disponible';
        ui.titulo.textContent = "No pudimos cargar la lección";
        ui.descripcion.textContent = mensaje;
        ui.contenedor.innerHTML = `
            <div class="text-center py-4">
                <div class="fs-1 mb-2">🧭</div>
                <p class="text-muted mb-4">${escaparHTML(mensaje)}</p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    ${urlRuta ? `<a href="${escaparHTML(urlRuta)}" class="btn btn-3d-orange"><i class="bi bi-diagram-3-fill me-1"></i> Volver a la ruta</a>` : ""}
                    <a href="${escaparHTML(ui.urlEjercicios)}" class="btn btn-3d-ghost">Práctica general</a>
                </div>
            </div>`;
    }

    // ==========================================================
    // SESIÓN DE LECCIÓN
    // ==========================================================
    class SesionLeccion {
        constructor(ui, banco, leccion, urlRuta) {
            this.ui = ui;
            this.banco = banco;
            this.leccion = leccion;
            this.urlRuta = urlRuta;
            this.fuente = "local";
        }

        arrancar() {
            this.pintarCabecera();
            this.pintarPalabrasClave();
            this.pintarEstructura();
            this.configurarModalApiKey();

            const conClave = window.ServicioIA && window.ServicioIA.tieneApiKey();
            if (this.leccion.personalizada && conClave) {
                // Las lecciones personales se benefician más de un reto generado
                this.generarConIA(true);
            } else {
                this.cargarEjercicios(this.leccion.ejercicios, "local");
            }
        }

        // ---------------- Cabecera y panel lateral ----------------
        pintarCabecera() {
            const { ui, banco, leccion } = this;
            document.title = `Shiftly | ${banco.nombre} · ${leccion.titulo}`;
            ui.badge.innerHTML = `<i class="bi ${escaparHTML(banco.icono || "bi-mortarboard-fill")}"></i> ${escaparHTML(banco.nombre)} · ${escaparHTML(leccion.etiqueta)}: ${escaparHTML(leccion.titulo)}`;
            ui.titulo.textContent = leccion.titulo;
            ui.descripcion.textContent = leccion.repaso
                ? `Vuelve a resolver ${leccion.ejercicios.length} ejercicio${leccion.ejercicios.length === 1 ? "" : "s"} que fallaste. Si aciertas a la primera, sale de tu lista de errores.`
                : `Completa los ${Math.min(leccion.ejercicios.length, 5)} retos para ganar +${leccion.xp} XP y desbloquear la siguiente lección de tu ruta.`;
            ui.consejo.textContent = leccion.consejo || "";
        }

        pintarPalabrasClave() {
            const { ui, leccion } = this;
            ui.badgePalabras.textContent = leccion.etiqueta;
            const filas = (leccion.terminos || []).map((t, i) => `
                <div class="p-2 rounded-3 d-flex justify-content-between align-items-center gap-2" style="background-color: var(--purple-light);">
                    <div class="min-w-0">
                        <strong style="color: var(--purple-dark);">${escaparHTML(t.en)}</strong>
                        <div class="text-muted" style="font-size: 0.78rem;">${escaparHTML(t.es)}</div>
                    </div>
                    <div class="d-flex flex-shrink-0">
                        <button type="button" class="btn btn-sm text-warning" data-hablar-termino="${i}" title="Escuchar pronunciación">
                            <i class="bi bi-volume-up-fill fs-5"></i>
                        </button>
                        ${MICROFONO_DISPONIBLE ? `
                        <button type="button" class="btn btn-sm btn-mic" data-mic-termino="${i}" title="Practicar pronunciación con micrófono">
                            <i class="bi bi-mic-fill fs-5"></i>
                        </button>` : ""}
                    </div>
                </div>`).join("");

            ui.palabrasClave.innerHTML = `
                ${filas}
                <div class="resultado-voz d-none" id="resultadoVoz" role="status"></div>
                <p class="small text-muted mb-0 mt-1">
                    ${MICROFONO_DISPONIBLE
                        ? '<i class="bi bi-mic me-1"></i>Toca el micrófono y pronuncia el término para evaluar tu pronunciación.'
                        : '<i class="bi bi-info-circle me-1"></i>Tu navegador no admite reconocimiento de voz (prueba Chrome o Edge).'}
                </p>`;

            ui.palabrasClave.addEventListener("click", (evento) => {
                const audio = evento.target.closest("[data-hablar-termino]");
                const mic = evento.target.closest("[data-mic-termino]");
                const terminos = this.leccion.terminos || [];
                if (audio) hablar(terminos[Number(audio.dataset.hablarTermino)].en);
                if (mic) escucharPronunciacion(terminos[Number(mic.dataset.micTermino)].en, mic, $("resultadoVoz"));
            });
        }

        // ---------------- Estructura de la tarjeta ----------------
        pintarEstructura() {
            this.ui.contenedor.innerHTML = `
                <div class="barra-ia mb-4">
                    <span class="fuente-badge" id="fuenteEjercicios"></span>
                    <div class="d-flex flex-wrap gap-2 ms-auto">
                        <button type="button" class="btn btn-3d-purple btn-ia d-none" id="btnGenerarIA">
                            <i class="bi bi-stars me-1"></i> Generar reto nuevo con ChatGPT
                        </button>
                        <button type="button" class="btn btn-3d-ghost btn-sm" data-bs-toggle="modal" data-bs-target="#modalApiKey">
                            <i class="bi bi-key-fill me-1"></i> Configurar API Key de ChatGPT / OpenAI
                        </button>
                    </div>
                    <div class="feedback-banner d-none w-100 mt-2 mb-0" id="avisoIA" role="status"></div>
                </div>
                <div id="zonaEjercicio"></div>
                <div class="feedback-banner d-none mt-3" id="feedbackLeccion" role="status"></div>
                <div class="d-flex flex-wrap align-items-center gap-2 mt-4 pt-3 border-top" id="accionesLeccion" style="border-color: var(--border-purple) !important;">
                    <button type="button" class="btn btn-3d-orange" id="btnComprobar">
                        <i class="bi bi-check-circle-fill me-1"></i> Comprobar respuesta
                    </button>
                    <button type="button" class="btn btn-3d-ghost d-none" id="btnPista">
                        <i class="bi bi-lightbulb-fill text-warning me-1"></i> Pista
                    </button>
                    <button type="button" class="btn btn-3d-purple ms-auto" id="btnSiguiente" disabled>
                        Siguiente ejercicio <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
                <div id="resumenLeccion" class="d-none"></div>`;

            $("btnComprobar").addEventListener("click", () => this.comprobar());
            $("btnSiguiente").addEventListener("click", () => this.siguiente());
            $("btnPista").addEventListener("click", () => {
                const ej = this.ejercicios[this.indice];
                this.mostrarFeedback("info", `<i class="bi bi-lightbulb-fill me-1"></i> <strong>Pista:</strong> ${escaparHTML(ej.pista || "Revisa las palabras clave del panel lateral.")}`);
            });
            $("btnGenerarIA").addEventListener("click", () => this.generarConIA(false));
            this.actualizarBotonIA();
        }

        actualizarBotonIA() {
            // En el repaso se practican los errores guardados, no retos nuevos
            const disponible = Boolean(window.ServicioIA && window.ServicioIA.tieneApiKey()) && !this.leccion.repaso;
            $("btnGenerarIA").classList.toggle("d-none", !disponible);
        }

        pintarFuente() {
            const fuente = $("fuenteEjercicios");
            fuente.className = `fuente-badge ${this.fuente}`;
            fuente.innerHTML = this.fuente === "ia"
                ? '<i class="bi bi-stars"></i> Reto generado con ChatGPT'
                : this.leccion.repaso
                    ? `<i class="bi bi-arrow-repeat"></i> Repaso · ${this.ejercicios.length} de ${this.leccion.totalErrores} errores`
                    : `<i class="bi bi-hdd-stack-fill"></i> Banco local · ${this.leccion.personalizada ? "práctica general" : `${this.ejercicios.length} retos de la lección`}`;
        }

        avisoIA(tipo, html) {
            const aviso = $("avisoIA");
            if (!tipo) {
                aviso.className = "feedback-banner d-none w-100 mt-2 mb-0";
                return;
            }
            aviso.className = `feedback-banner ${tipo} d-block w-100 mt-2 mb-0`;
            aviso.innerHTML = html;
        }

        // ---------------- Carga de ejercicios ----------------
        cargarEjercicios(lista, fuente) {
            this.ejercicios = lista.slice(0, 5);
            this.fuente = fuente;
            this.indice = 0;
            this.resueltos = new Set();
            this.primerIntento = 0;
            this.fallosEnActual = 0;
            this.vidas = TOTAL_VIDAS;
            this.racha = 0;
            this.xpGanado = 0;
            this.inicio = Date.now();
            this.dominados = [];

            $("resumenLeccion").classList.add("d-none");
            $("zonaEjercicio").classList.remove("d-none");
            $("accionesLeccion").classList.remove("d-none");
            this.pintarFuente();
            this.actualizarStats();
            this.mostrarEjercicio();
        }

        async generarConIA(automatico) {
            const boton = $("btnGenerarIA");
            boton.disabled = true;
            boton.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Generando reto…';
            this.avisoIA("info", '<i class="bi bi-stars me-1"></i> ChatGPT está creando ejercicios nuevos para esta lección…');

            try {
                const generados = await window.ServicioIA.generar({
                    carrera: this.banco.nombre,
                    leccion: this.leccion.titulo,
                    temas: this.leccion.temas,
                    nivel: obtenerNivel(),
                    cantidad: 5
                });
                // Si ChatGPT devolvió menos de 5 válidos, se completa con el banco local
                const relleno = barajar(this.leccion.ejercicios).filter((e) => !generados.some((g) => g.tipo === e.tipo));
                const lista = [...generados, ...relleno].slice(0, 5);
                this.avisoIA("success", `<i class="bi bi-check-circle-fill me-1"></i> ¡Reto nuevo listo! ${generados.length} de 5 ejercicios generados con ChatGPT.`);
                this.cargarEjercicios(lista, "ia");
            } catch (error) {
                // Al abrir la lección se usa el banco local; si ya había ejercicios, se conservan
                const usarLocal = automatico || !this.ejercicios;
                const detalle = usarLocal ? "Se cargó el banco local de la lección." : "Se conservan los ejercicios actuales.";
                this.avisoIA("error", `<i class="bi bi-exclamation-triangle-fill me-1"></i> ${escaparHTML(error.message)} ${detalle}`);
                if (usarLocal) this.cargarEjercicios(this.leccion.ejercicios, "local");
            } finally {
                boton.disabled = false;
                boton.innerHTML = '<i class="bi bi-stars me-1"></i> Generar reto nuevo con ChatGPT';
            }
        }

        // ---------------- Estadísticas ----------------
        actualizarStats() {
            const { ui } = this;
            const total = this.ejercicios.length;
            const hechos = this.resueltos.size;
            ui.vidas.textContent = this.vidas;
            ui.xp.textContent = this.xpGanado;
            ui.racha.textContent = this.racha;
            ui.barra.style.width = `${Math.round((hechos / total) * 100)}%`;
            ui.textoProgreso.textContent = `${hechos} de ${total} ejercicios completados`;
        }

        xpPorEjercicio(indice) {
            const total = this.ejercicios.length;
            const base = Math.floor(this.leccion.xp / total);
            return indice === total - 1 ? this.leccion.xp - base * (total - 1) : base;
        }

        // ---------------- Render de cada ejercicio ----------------
        mostrarEjercicio() {
            const ej = this.ejercicios[this.indice];
            const tipo = TIPOS[ej.tipo];
            const { ui } = this;

            ui.tipoIcono.textContent = tipo.icono;
            ui.tipoNombre.textContent = tipo.nombre;
            ui.contador.textContent = `Ejercicio ${this.indice + 1} de ${this.ejercicios.length}`;

            this.fallosEnActual = 0;
            this.seleccion = null;
            this.mostrarFeedback(null);
            $("btnSiguiente").disabled = true;
            $("btnSiguiente").innerHTML = this.indice === this.ejercicios.length - 1
                ? 'Ver resultados <i class="bi bi-trophy-fill ms-1"></i>'
                : 'Siguiente ejercicio <i class="bi bi-arrow-right ms-1"></i>';
            $("btnComprobar").classList.toggle("d-none", ej.tipo === "emparejar");
            $("btnComprobar").disabled = false;
            $("btnPista").classList.toggle("d-none", ej.tipo !== "completar");

            const zona = $("zonaEjercicio");
            zona.innerHTML = `
                <h2 class="h4 fw-extrabold mb-1" style="color: var(--purple-dark);">${escaparHTML(ej.enunciado)}</h2>
                <p class="text-muted small mb-4">${this.instruccion(ej.tipo)}</p>
                ${this.htmlEjercicio(ej)}`;
            this.enlazarEjercicio(ej, zona);
        }

        instruccion(tipo) {
            return {
                opcion: "Selecciona la opción que completa correctamente la frase técnica.",
                completar: "Escribe la palabra que falta. Puedes escuchar la frase con el botón de audio.",
                ordenar: "Toca las palabras en orden. Toca un espacio lleno para devolver la palabra.",
                emparejar: "Toca un término en inglés y luego su traducción en español.",
                escuchar: "Escucha el audio en inglés y elige la traducción correcta."
            }[tipo];
        }

        botonAudio(texto, titulo = "Escuchar en inglés") {
            return `<button type="button" class="btn-audio-sentence" data-audio="${escaparHTML(texto)}" title="${titulo}">
                        <i class="bi bi-volume-up-fill"></i>
                    </button>`;
        }

        // Frase de referencia en inglés (para audio y repaso)
        fraseCompleta(ej) {
            switch (ej.tipo) {
                case "opcion": return ej.frase.replace("___", ej.opciones[ej.correcta]);
                case "completar": return `${ej.antes} ${ej.respuestas[0]} ${ej.despues}`.replace(/\s+([.,!?])/g, "$1").trim();
                case "ordenar": return `${ej.prefijo ? ej.prefijo + " " : ""}${ej.palabras.join(" ")}${ej.sufijo || ""}`;
                case "escuchar": return ej.audio;
                default: return "";
            }
        }

        htmlEjercicio(ej) {
            switch (ej.tipo) {
                case "opcion": {
                    // Antes de responder el audio lee la frase con una pausa en el hueco
                    const audioSinRespuesta = ej.frase.replace("___", "…");
                    const opciones = barajar(ej.opciones.map((texto, i) => ({ texto, correcta: i === ej.correcta })));
                    return `
                        <div class="sentence-box d-flex align-items-center justify-content-between gap-3 mb-4">
                            <div>${escaparHTML(ej.frase).replace("___", '<span class="px-3 py-1 rounded-3" style="background-color: var(--purple-soft); color: var(--purple-main);">_____</span>')}</div>
                            ${this.botonAudio(audioSinRespuesta)}
                        </div>
                        <div class="row g-2" id="opcionesEj">
                            ${opciones.map((o, i) => `
                                <div class="col-sm-6">
                                    <button class="option-card-3d" type="button" data-correcta="${o.correcta}">
                                        <span class="option-letter">${"ABCD"[i]}</span> ${escaparHTML(o.texto)}
                                    </button>
                                </div>`).join("")}
                        </div>`;
                }
                case "completar":
                    return `
                        <div class="sentence-box d-flex align-items-center justify-content-between gap-3 mb-2">
                            <div>
                                ${escaparHTML(ej.antes)}
                                <input id="respuestaCompletar" class="input-answer-3d" type="text" placeholder="escribe…" autocomplete="off" autocapitalize="off" spellcheck="false" aria-label="Palabra que falta">
                                ${escaparHTML(ej.despues)}
                            </div>
                            ${this.botonAudio(`${ej.antes} … ${ej.despues}`)}
                        </div>`;
                case "ordenar": {
                    this.colocadas = Array(ej.palabras.length).fill(null);
                    const fichas = barajar([...ej.palabras, ...(ej.distractores || [])].map((palabra, i) => ({ palabra, i })));
                    return `
                        <div class="sentence-box mb-4">
                            ${ej.prefijo ? escaparHTML(ej.prefijo) + " " : ""}
                            ${ej.palabras.map((_, i) => `<button class="blank-slot" data-slot="${i}" type="button">_____</button>`).join(" ")}${escaparHTML(ej.sufijo || "")}
                        </div>
                        <div class="small fw-extrabold text-uppercase mb-2" style="color: var(--purple-main);">Banco de palabras:</div>
                        <div class="d-flex flex-wrap gap-2" id="bancoPalabras">
                            ${fichas.map((f) => `<button class="word-chip" data-ficha="${f.i}" data-palabra="${escaparHTML(f.palabra)}" type="button">${escaparHTML(f.palabra)}</button>`).join("")}
                        </div>
                        <button class="btn btn-3d-ghost btn-sm mt-3" id="reiniciarOrden" type="button">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reiniciar
                        </button>`;
                }
                case "emparejar": {
                    this.paresHechos = 0;
                    this.primeraSeleccion = null;
                    const ingles = barajar(ej.pares.map((p, i) => ({ texto: p.en, par: i })));
                    const espanol = barajar(ej.pares.map((p, i) => ({ texto: p.es, par: i })));
                    const columna = (lista, lado, color) => lista.map((x) => `
                        <button class="match-btn-3d mb-2" data-lado="${lado}" data-par="${x.par}" type="button">
                            <span>${escaparHTML(x.texto)}</span>
                            <span class="badge" style="${color}">${lado.toUpperCase()}</span>
                        </button>`).join("");
                    return `
                        <div class="row g-3" id="gridPares">
                            <div class="col-6">${columna(ingles, "en", "background-color: var(--purple-soft); color: var(--purple-main);")}</div>
                            <div class="col-6">${columna(espanol, "es", "background-color: var(--orange-light); color: var(--orange-bright);")}</div>
                        </div>`;
                }
                case "escuchar": {
                    const opciones = barajar(ej.opciones.map((texto, i) => ({ texto, correcta: i === ej.correcta })));
                    return `
                        <div class="p-4 rounded-4 text-center mb-4" style="background-color: var(--purple-light); border: 2px solid var(--border-purple);">
                            <button type="button" class="btn btn-3d-purple px-4 py-3 fs-5 mb-2" data-audio="${escaparHTML(ej.audio)}">
                                <i class="bi bi-volume-up-fill me-2"></i> Escuchar Audio
                            </button>
                            <div class="d-flex justify-content-center gap-3 small fw-bold text-muted">
                                <span>Pronunciación EE.UU.</span>
                                <button type="button" class="btn btn-link btn-sm p-0 fw-bold" data-audio-lento="${escaparHTML(ej.audio)}">
                                    <i class="bi bi-speedometer me-1"></i>Más lento
                                </button>
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-2" id="opcionesEj">
                            ${opciones.map((o, i) => `
                                <button class="option-card-3d" type="button" data-correcta="${o.correcta}">
                                    <span class="option-letter">${"ABCD"[i]}</span> ${escaparHTML(o.texto)}
                                </button>`).join("")}
                        </div>`;
                }
                default:
                    return "";
            }
        }

        enlazarEjercicio(ej, zona) {
            // Audio en cualquier botón con data-audio
            zona.querySelectorAll("[data-audio]").forEach((b) => b.addEventListener("click", () => hablar(b.dataset.audio)));
            zona.querySelectorAll("[data-audio-lento]").forEach((b) => b.addEventListener("click", () => hablar(b.dataset.audioLento, "en-US", 0.65)));

            if (ej.tipo === "opcion" || ej.tipo === "escuchar") {
                const botones = zona.querySelectorAll("#opcionesEj .option-card-3d");
                botones.forEach((b) => b.addEventListener("click", () => {
                    if (this.resueltos.has(this.indice)) return;
                    botones.forEach((x) => x.classList.remove("selected", "wrong"));
                    b.classList.add("selected");
                    this.seleccion = b;
                }));
            }

            if (ej.tipo === "completar") {
                const input = $("respuestaCompletar");
                input.focus({ preventScroll: true });
                input.addEventListener("keydown", (e) => {
                    if (e.key === "Enter") this.comprobar();
                });
            }

            if (ej.tipo === "ordenar") {
                const huecos = Array.from(zona.querySelectorAll(".blank-slot"));
                const fichas = Array.from(zona.querySelectorAll(".word-chip"));
                const sincronizar = () => {
                    huecos.forEach((h, i) => {
                        const ficha = this.colocadas[i];
                        h.textContent = ficha ? ficha.dataset.palabra : "_____";
                        h.classList.toggle("filled", Boolean(ficha));
                    });
                    $("btnComprobar").disabled = this.colocadas.includes(null);
                };
                fichas.forEach((f) => f.addEventListener("click", () => {
                    const libre = this.colocadas.indexOf(null);
                    if (libre === -1 || this.resueltos.has(this.indice)) return;
                    this.colocadas[libre] = f;
                    f.disabled = true;
                    sincronizar();
                }));
                huecos.forEach((h, i) => h.addEventListener("click", () => {
                    const ficha = this.colocadas[i];
                    if (!ficha || this.resueltos.has(this.indice)) return;
                    ficha.disabled = false;
                    this.colocadas[i] = null;
                    sincronizar();
                }));
                $("reiniciarOrden").addEventListener("click", () => {
                    if (this.resueltos.has(this.indice)) return;
                    this.colocadas = this.colocadas.map(() => null);
                    fichas.forEach((f) => { f.disabled = false; });
                    this.mostrarFeedback(null);
                    sincronizar();
                });
                sincronizar();
            }

            if (ej.tipo === "emparejar") {
                const botones = Array.from(zona.querySelectorAll(".match-btn-3d"));
                botones.forEach((b) => b.addEventListener("click", () => this.elegirPar(ej, b)));
            }
        }

        elegirPar(ej, boton) {
            if (boton.classList.contains("matched") || boton === this.primeraSeleccion) return;
            if (boton.dataset.lado === "en") hablar(boton.querySelector("span").textContent.trim());

            if (!this.primeraSeleccion || this.primeraSeleccion.dataset.lado === boton.dataset.lado) {
                if (this.primeraSeleccion) this.primeraSeleccion.classList.remove("active-pair");
                this.primeraSeleccion = boton;
                boton.classList.add("active-pair");
                return;
            }

            const primera = this.primeraSeleccion;
            this.primeraSeleccion = null;
            if (primera.dataset.par === boton.dataset.par) {
                primera.classList.remove("active-pair");
                primera.classList.add("matched");
                boton.classList.add("matched");
                this.paresHechos++;
                if (this.paresHechos === ej.pares.length) {
                    this.acierto(ej, "¡Completaste todos los pares correctamente!");
                }
            } else {
                primera.classList.add("error-pair");
                boton.classList.add("error-pair");
                this.error("Esas palabras no coinciden. ¡Inténtalo de nuevo!");
                setTimeout(() => {
                    primera.classList.remove("active-pair", "error-pair");
                    boton.classList.remove("error-pair");
                }, 450);
            }
        }

        // ---------------- Comprobación ----------------
        comprobar() {
            if (this.resueltos.has(this.indice)) return;
            const ej = this.ejercicios[this.indice];

            if (ej.tipo === "opcion" || ej.tipo === "escuchar") {
                if (!this.seleccion) {
                    this.mostrarFeedback("info", '<i class="bi bi-hand-index-thumb me-1"></i> Selecciona una opción primero.');
                    return;
                }
                if (this.seleccion.dataset.correcta === "true") {
                    this.seleccion.classList.add("correct");
                    this.acierto(ej, "¡Correcto!");
                } else {
                    this.seleccion.classList.add("wrong");
                    this.error("Esa no es la opción correcta. Inténtalo otra vez.");
                }
                return;
            }

            if (ej.tipo === "completar") {
                const input = $("respuestaCompletar");
                const valor = normalizarTexto(input.value);
                if (!valor) {
                    this.mostrarFeedback("info", '<i class="bi bi-pencil me-1"></i> Escribe tu respuesta primero.');
                    return;
                }
                if (ej.respuestas.some((r) => normalizarTexto(r) === valor)) {
                    input.disabled = true;
                    this.acierto(ej, `¡Correcto! «${escaparHTML(this.fraseCompleta(ej))}»`);
                } else {
                    this.error("Revisa la palabra. Puedes usar la pista.");
                }
                return;
            }

            if (ej.tipo === "ordenar") {
                const frase = this.colocadas.map((f) => (f ? f.dataset.palabra : "")).join(" ");
                if (frase === ej.palabras.join(" ")) {
                    this.acierto(ej, `¡Excelente! «${escaparHTML(this.fraseCompleta(ej))}»`);
                } else {
                    this.error("El orden no es correcto. Toca un espacio para devolver la palabra.");
                }
            }
        }

        acierto(ej, mensaje) {
            this.resueltos.add(this.indice);
            if (this.fallosEnActual === 0) this.primerIntento++;
            // En repaso, acertar a la primera saca el ejercicio de la lista de errores
            if (this.leccion.repaso && this.fallosEnActual === 0) {
                this.dominados.push(this.leccion.clavesRepaso[this.indice]);
            }
            this.racha++;
            this.xpGanado += this.xpPorEjercicio(this.indice);
            this.actualizarStats();

            const extra = ej.explicacion ? `<div class="small mt-1">${escaparHTML(ej.explicacion)}</div>` : "";
            this.mostrarFeedback("success", `<i class="bi bi-check-circle-fill me-1"></i> ${mensaje}${extra}`);
            const frase = this.fraseCompleta(ej);
            if (frase) hablar(frase);

            $("btnComprobar").disabled = true;
            $("btnSiguiente").disabled = false;
            $("btnSiguiente").focus({ preventScroll: true });
        }

        error(mensaje) {
            this.fallosEnActual++;
            // El primer fallo de cada ejercicio se guarda para el repaso
            if (this.fallosEnActual === 1 && !this.leccion.repaso) {
                registrarError(this.banco, this.leccion, this.ejercicios[this.indice]);
            }
            this.racha = 0;
            if (this.vidas > 0) this.vidas--;
            this.actualizarStats();
            this.mostrarFeedback("error", `<i class="bi bi-x-circle-fill me-1"></i> ${mensaje}`);
        }

        mostrarFeedback(tipo, html) {
            const caja = $("feedbackLeccion");
            if (!tipo) {
                caja.className = "feedback-banner d-none mt-3";
                return;
            }
            caja.className = `feedback-banner ${tipo} d-block mt-3`;
            caja.innerHTML = html;
        }

        siguiente() {
            if (!this.resueltos.has(this.indice)) return;
            if (this.indice < this.ejercicios.length - 1) {
                this.indice++;
                this.mostrarEjercicio();
            } else {
                this.finalizar();
            }
        }

        // ---------------- Final de la lección ----------------
        finalizar() {
            if (this.leccion.repaso) {
                this.finalizarRepaso();
                return;
            }
            const total = this.ejercicios.length;
            const resultado = guardarResultado(this.banco, this.leccion, {
                aciertosPrimerIntento: this.primerIntento,
                total,
                vidas: this.vidas,
                fuente: this.fuente,
                duracionSeg: Math.round((Date.now() - this.inicio) / 1000)
            });
            // Racha diaria y meta del día: suma XP solo si la lección se completó por primera vez
            registrarActividadDiaria(resultado.estado === "nueva" ? this.leccion.xp : 0);

            const nombreRuta = `Ruta de ${this.banco.nombre}`;
            let titulo = "¡Lección completada!";
            let texto = `Ganaste <strong>+${this.leccion.xp} XP</strong>. El nodo «${escaparHTML(this.leccion.titulo)}» ya aparece en verde en tu ${escaparHTML(nombreRuta)}.`;
            if (resultado.siguiente) {
                texto += ` Se desbloqueó: <strong>${escaparHTML(resultado.siguiente.titulo)}</strong>.`;
            } else {
                texto += " ¡Completaste toda la ruta!";
            }
            if (resultado.estado === "repaso") {
                titulo = "¡Repaso completado!";
                texto = "Esta lección ya estaba completada, así que tu XP no cambió. ¡Buen repaso!";
            }
            if (resultado.estado === "bloqueada") {
                titulo = "¡Buena práctica!";
                texto = `Esta lección todavía está bloqueada en tu ruta, así que no se marcó como completada. Primero completa «${escaparHTML(resultado.actualAntes ? resultado.actualAntes.titulo : "la lección anterior")}».`;
            }

            const siguienteEsBase = resultado.siguiente && this.banco.lecciones.some((l) => l.id === resultado.siguiente.id);
            const parametroSiguiente = resultado.siguiente
                ? (siguienteEsBase ? resultado.siguiente.numero : resultado.siguiente.id)
                : null;
            const urlSiguiente = parametroSiguiente
                ? `${this.ui.urlEjercicios}?carrera=${encodeURIComponent(this.banco.slug)}&leccion=${encodeURIComponent(parametroSiguiente)}`
                : null;

            $("zonaEjercicio").classList.add("d-none");
            $("accionesLeccion").classList.add("d-none");
            this.mostrarFeedback(null);
            this.avisoIA(null);

            const resumen = $("resumenLeccion");
            resumen.classList.remove("d-none");
            resumen.innerHTML = `
                <div class="text-center py-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px; background: linear-gradient(135deg, var(--orange-bright), #FF9642); color: var(--white); font-size: 2.8rem; box-shadow: 0 8px 20px rgba(255,107,53,0.35);">
                        <i class="bi ${resultado.estado === "bloqueada" ? "bi-lock-fill" : "bi-trophy-fill"}"></i>
                    </div>
                    <h2 class="display-6 fw-extrabold mb-2" style="color: var(--purple-dark);">${titulo}</h2>
                    <p class="text-muted mb-4 mx-auto" style="max-width: 520px;">${texto}</p>

                    <div class="row g-3 justify-content-center mb-4 mx-auto" style="max-width: 480px;">
                        <div class="col-4">
                            <div class="p-3 rounded-4" style="background-color: var(--purple-soft);">
                                <div class="fw-extrabold fs-4" style="color: var(--purple-main);">+${resultado.estado === "nueva" ? this.leccion.xp : 0}</div>
                                <div class="small fw-bold text-muted">XP ganados</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded-4" style="background-color: var(--orange-light);">
                                <div class="fw-extrabold fs-4" style="color: var(--orange-bright);">${this.primerIntento}/${total}</div>
                                <div class="small fw-bold text-muted">A la primera</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded-4" style="background-color: var(--green-soft);">
                                <div class="fw-extrabold fs-4 text-success">${this.vidas}</div>
                                <div class="small fw-bold text-muted">Vidas</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="${escaparHTML(this.urlRuta)}" class="btn btn-3d-orange" id="btnVolverRuta">
                            <i class="bi bi-diagram-3-fill me-1"></i> Volver a ${escaparHTML(nombreRuta)}
                        </a>
                        ${urlSiguiente && resultado.estado === "nueva" ? `
                        <a href="${escaparHTML(urlSiguiente)}" class="btn btn-3d-purple">
                            Siguiente lección <i class="bi bi-arrow-right-circle-fill ms-1"></i>
                        </a>` : ""}
                        <button type="button" class="btn btn-3d-ghost" id="btnRepetirLeccion">
                            <i class="bi bi-arrow-repeat me-1"></i> Practicar de nuevo
                        </button>
                    </div>
                </div>`;

            $("btnRepetirLeccion").addEventListener("click", () => {
                this.cargarEjercicios(this.fuente === "ia" ? this.ejercicios : this.leccion.ejercicios, this.fuente);
            });
            this.ui.contador.textContent = "Resultados";
            this.ui.tipoIcono.textContent = "🏆";
            this.ui.tipoNombre.textContent = "Lección terminada";
        }

        // ---------------- Final del repaso de errores ----------------
        finalizarRepaso() {
            const total = this.ejercicios.length;
            const restantes = quitarErrores(this.banco, this.dominados);
            registrarActividadDiaria(0);

            const nombreRuta = `Ruta de ${this.banco.nombre}`;
            const repasarUrl = `${this.ui.urlEjercicios}?carrera=${encodeURIComponent(this.banco.slug)}&repaso=1`;
            $("zonaEjercicio").classList.add("d-none");
            $("accionesLeccion").classList.add("d-none");
            this.mostrarFeedback(null);
            this.avisoIA(null);

            const resumen = $("resumenLeccion");
            resumen.classList.remove("d-none");
            resumen.innerHTML = `
                <div class="text-center py-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px; background: linear-gradient(135deg, var(--purple-main), var(--purple-medium)); color: var(--white); font-size: 2.6rem; box-shadow: 0 8px 20px rgba(82,21,78,0.3);">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <h2 class="display-6 fw-extrabold mb-2" style="color: var(--purple-dark);">${restantes ? "¡Repaso terminado!" : "¡Errores superados!"}</h2>
                    <p class="text-muted mb-4 mx-auto" style="max-width: 520px;">
                        Dominaste <strong>${this.dominados.length} de ${total}</strong> ejercicios a la primera.
                        ${restantes
                            ? `Te quedan <strong>${restantes}</strong> por repasar.`
                            : "Ya no tienes errores pendientes en esta ruta."}
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="${escaparHTML(this.urlRuta)}" class="btn btn-3d-orange">
                            <i class="bi bi-diagram-3-fill me-1"></i> Volver a ${escaparHTML(nombreRuta)}
                        </a>
                        ${restantes ? `
                        <a href="${escaparHTML(repasarUrl)}" class="btn btn-3d-purple">
                            <i class="bi bi-arrow-repeat me-1"></i> Seguir repasando
                        </a>` : ""}
                    </div>
                </div>`;
            this.ui.contador.textContent = "Resultados";
            this.ui.tipoIcono.textContent = "🔁";
            this.ui.tipoNombre.textContent = "Repaso terminado";
        }

        // ---------------- Modal de API Key ----------------
        configurarModalApiKey() {
            const modal = $("modalApiKey");
            if (!modal || !window.ServicioIA) return;
            const input = $("inputApiKey");
            const estado = $("estadoApiKey");
            const btnGuardar = $("btnGuardarApiKey");
            const btnBorrar = $("btnBorrarApiKey");
            const btnVer = $("btnVerApiKey");

            const pintarEstado = () => {
                const clave = window.ServicioIA.obtenerApiKey();
                estado.className = `small fw-bold mb-3 ${clave ? "text-success" : "text-muted"}`;
                estado.innerHTML = clave
                    ? `<i class="bi bi-check-circle-fill me-1"></i> Clave guardada en este navegador: ${escaparHTML(window.ServicioIA.enmascarar(clave))}`
                    : '<i class="bi bi-info-circle me-1"></i> Sin clave: se usan los ejercicios locales.';
                btnBorrar.classList.toggle("d-none", !clave);
            };

            modal.addEventListener("show.bs.modal", () => {
                input.value = "";
                input.type = "password";
                input.classList.remove("is-invalid");
                pintarEstado();
            });

            btnVer.addEventListener("click", () => {
                input.type = input.type === "password" ? "text" : "password";
                btnVer.innerHTML = `<i class="bi ${input.type === "password" ? "bi-eye" : "bi-eye-slash"}"></i>`;
            });

            btnGuardar.addEventListener("click", () => {
                const valor = input.value.trim();
                if (valor.length < 20 || /\s/.test(valor)) {
                    input.classList.add("is-invalid");
                    return;
                }
                window.ServicioIA.guardarApiKey(valor);
                input.value = "";
                pintarEstado();
                this.actualizarBotonIA();
                bootstrap.Modal.getOrCreateInstance(modal).hide();
                this.avisoIA("success", '<i class="bi bi-key-fill me-1"></i> API Key guardada. Ya puedes generar retos nuevos con ChatGPT.');
            });

            btnBorrar.addEventListener("click", () => {
                window.ServicioIA.borrarApiKey();
                pintarEstado();
                this.actualizarBotonIA();
                this.avisoIA("info", '<i class="bi bi-trash3 me-1"></i> API Key eliminada de este navegador. Se usarán los ejercicios locales.');
            });
        }
    }
})();
