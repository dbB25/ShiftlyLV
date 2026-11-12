// ==========================================
// AUDIO NATIVO (TEXT-TO-SPEECH)
// ==========================================
function speakSentence(text, lang = 'en-US') {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = lang;
        utterance.rate = 0.92;
        window.speechSynthesis.speak(utterance);
    }
}

// ==========================================
// MOSTRAR / OCULTAR PISTA
// ==========================================
function toggleHintBox(id) {
    const box = document.getElementById(id);
    if (box) box.classList.toggle('d-none');
}

document.addEventListener('DOMContentLoaded', () => {
    const exercises = Array.from(document.querySelectorAll('.exercise'));
    const totalExercises = exercises.length;

    let currentIndex = 0;
    let completedSet = new Set();
    let hearts = 5;
    let xp = 0;
    let streak = 0;

    // Elementos UI
    const typeIcon = document.getElementById('typeIcon');
    const typeName = document.getElementById('typeName');
    const counter = document.getElementById('counter');
    const tipText = document.getElementById('tip');
    const progressCopy = document.querySelector('.progress-copy');
    const progressBar = document.getElementById('mainProgressBar');
    const nextBtn = document.getElementById('nextExercise');
    const lessonSummary = document.getElementById('lessonSummary');
    const lessonFooterNav = document.getElementById('lessonFooterNav');

    const heartsEl = document.getElementById('heartsCount');
    const xpEl = document.getElementById('xpCount');
    const streakEl = document.getElementById('streakCount');

    function updateStatsUI() {
        heartsEl.textContent = hearts;
        xpEl.textContent = xp;
        streakEl.textContent = streak;

        const completedCount = completedSet.size;
        const pct = Math.round((completedCount / totalExercises) * 100);
        progressBar.style.width = `${pct}%`;
        progressCopy.textContent = `${completedCount} de ${totalExercises} ejercicios completados`;
    }

    function registerSuccess(index, feedbackEl, message) {
        feedbackEl.className = 'feedback-banner success d-block';
        feedbackEl.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> ${message}`;

        if (!completedSet.has(index)) {
            completedSet.add(index);
            streak++;
            xp += 20 + (streak * 5);
            updateStatsUI();
        }
    }

    function registerError(feedbackEl, message) {
        feedbackEl.className = 'feedback-banner error d-block';
        feedbackEl.innerHTML = `<i class="bi bi-x-circle-fill me-1"></i> ${message}`;
        streak = 0;
        if (hearts > 1) hearts--;
        updateStatsUI();
    }

    function showExercise(index) {
        if (index >= totalExercises) {
            // Mostrar pantalla final de resumen
            exercises.forEach(ex => ex.hidden = true);
            lessonFooterNav.classList.add('d-none');
            lessonSummary.classList.remove('d-none');
            document.getElementById('finalXp').textContent = `+${xp}`;
            document.getElementById('finalScore').textContent = `${completedSet.size}/${totalExercises}`;
            document.getElementById('finalHearts').textContent = hearts;
            return;
        }

        currentIndex = index;
        exercises.forEach((ex, i) => {
            ex.hidden = i !== index;
            ex.classList.toggle('active', i === index);
        });

        const current = exercises[index];
        typeIcon.textContent = current.getAttribute('data-icon') || '📘';
        typeName.textContent = current.getAttribute('data-type') || 'Ejercicio';
        counter.textContent = `Ejercicio ${index + 1} de ${totalExercises}`;
        tipText.textContent = current.getAttribute('data-tip') || '';

        nextBtn.innerHTML = index === totalExercises - 1
            ? 'Ver resultados <i class="bi bi-trophy-fill ms-1"></i>'
            : 'Siguiente ejercicio <i class="bi bi-arrow-right ms-1"></i>';
    }

    // Navegación Siguiente / Omitir
    nextBtn.addEventListener('click', () => showExercise(currentIndex + 1));
    document.querySelectorAll('[data-next]').forEach(btn => {
        btn.addEventListener('click', () => showExercise(currentIndex + 1));
    });

    // ==========================================
    // EJERCICIO 1: COMPLETAR FRASE
    // ==========================================
    const fillInput = document.getElementById('fillInput');
    const checkFill = document.getElementById('checkFill');
    const fillFeedback = document.getElementById('fillFeedback');

    checkFill.addEventListener('click', () => {
        const val = fillInput.value.trim().toLowerCase();
        if (val === 'drink' || val === 'have') {
            speakSentence('I drink coffee every morning before work.');
            registerSuccess(0, fillFeedback, '¡Correcto! "I drink coffee every morning before work." (+25 XP)');
        } else {
            registerError(fillFeedback, 'Intenta de nuevo. Usa el verbo en forma base: "drink".');
        }
    });

    // ==========================================
    // EJERCICIO 2: ORDENA LAS PALABRAS
    // ==========================================
    const blanks = Array.from(document.querySelectorAll('.blank-slot'));
    const wordChips = Array.from(document.querySelectorAll('.word-chip'));
    const checkDrag = document.getElementById('checkDrag');
    const resetDrag = document.getElementById('resetDrag');
    const dragFeedback = document.getElementById('dragFeedback');
    let placedWords = [null, null, null];

    function syncSlots() {
        blanks.forEach((slot, idx) => {
            if (placedWords[idx]) {
                slot.textContent = placedWords[idx];
                slot.classList.add('filled');
            } else {
                slot.textContent = '_____';
                slot.classList.remove('filled');
            }
        });
        checkDrag.disabled = placedWords.includes(null);
    }

    wordChips.forEach(chip => {
        chip.addEventListener('click', () => {
            const emptyIdx = placedWords.indexOf(null);
            if (emptyIdx === -1) return;
            placedWords[emptyIdx] = chip.getAttribute('data-word');
            chip.disabled = true;
            syncSlots();
        });
    });

    blanks.forEach((slot, idx) => {
        slot.addEventListener('click', () => {
            const word = placedWords[idx];
            if (!word) return;
            placedWords[idx] = null;
            const chip = wordChips.find(c => c.getAttribute('data-word') === word);
            if (chip) chip.disabled = false;
            syncSlots();
        });
    });

    resetDrag.addEventListener('click', () => {
        placedWords = [null, null, null];
        wordChips.forEach(c => c.disabled = false);
        dragFeedback.className = 'feedback-banner d-none';
        syncSlots();
    });

    checkDrag.addEventListener('click', () => {
        const sentence = placedWords.join(' ');
        if (sentence === 'does every morning') {
            speakSentence('She does yoga every morning.');
            registerSuccess(1, dragFeedback, '¡Excelente! "She does yoga every morning."');
        } else {
            registerError(dragFeedback, 'El orden correcto es: She [does] yoga [every] [morning].');
        }
    });

    // ==========================================
    // EJERCICIO 3: OPCIÓN MÚLTIPLE
    // ==========================================
    const choiceBtns = Array.from(document.querySelectorAll('#choiceOptions .option-card-3d'));
    const checkChoice = document.getElementById('checkChoice');
    const choiceFeedback = document.getElementById('choiceFeedback');
    let selectedChoice = null;

    choiceBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            choiceBtns.forEach(b => b.classList.remove('selected', 'correct', 'wrong'));
            btn.classList.add('selected');
            selectedChoice = btn;
        });
    });

    checkChoice.addEventListener('click', () => {
        if (!selectedChoice) return;
        const ans = selectedChoice.getAttribute('data-answer');
        if (ans === 'go') {
            selectedChoice.classList.add('correct');
            speakSentence('They go to the office by bus.');
            registerSuccess(2, choiceFeedback, '¡Muy bien! Con "They" usamos "go" sin "-es".');
        } else {
            selectedChoice.classList.add('wrong');
            registerError(choiceFeedback, 'Incorrecto. La opción correcta es "B) go".');
        }
    });

    // ==========================================
    // EJERCICIO 4: EMPAREJA LOS PARES
    // ==========================================
    const matchBtns = Array.from(document.querySelectorAll('#matchGrid .match-btn-3d'));
    const matchFeedback = document.getElementById('matchFeedback');
    let firstSelection = null;
    let matchedPairs = 0;

    matchBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.classList.contains('matched') || btn === firstSelection) return;

            if (!firstSelection) {
                firstSelection = btn;
                btn.classList.add('active-pair');
                return;
            }

            const sameSide = firstSelection.getAttribute('data-side') === btn.getAttribute('data-side');
            if (sameSide) {
                firstSelection.classList.remove('active-pair');
                firstSelection = btn;
                btn.classList.add('active-pair');
                return;
            }

            const isMatch = firstSelection.getAttribute('data-pair') === btn.getAttribute('data-pair');
            if (isMatch) {
                firstSelection.classList.remove('active-pair');
                firstSelection.classList.add('matched');
                btn.classList.add('matched');
                speakSentence(btn.getAttribute('data-pair'));
                firstSelection = null;
                matchedPairs++;

                if (matchedPairs === 3) {
                    registerSuccess(3, matchFeedback, '¡Completaste todos los pares correctamente!');
                }
            } else {
                const prev = firstSelection;
                prev.classList.add('error-pair');
                btn.classList.add('error-pair');
                registerError(matchFeedback, 'Esas palabras no coinciden. ¡Inténtalo de nuevo!');
                setTimeout(() => {
                    prev.classList.remove('active-pair', 'error-pair');
                    btn.classList.remove('error-pair');
                }, 450);
                firstSelection = null;
            }
        });
    });

    // ==========================================
    // EJERCICIO 5: LISTENING
    // ==========================================
    const listenBtns = Array.from(document.querySelectorAll('#listenOptions .option-card-3d'));
    const checkListen = document.getElementById('checkListen');
    const listenFeedback = document.getElementById('listenFeedback');
    let selectedListen = null;

    listenBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            listenBtns.forEach(b => b.classList.remove('selected', 'correct', 'wrong'));
            btn.classList.add('selected');
            selectedListen = btn;
        });
    });

    checkListen.addEventListener('click', () => {
        if (!selectedListen) return;
        if (selectedListen.getAttribute('data-correct') === 'true') {
            selectedListen.classList.add('correct');
            registerSuccess(4, listenFeedback, '¡Oído fino! "We have a team meeting at nine o\'clock."');
        } else {
            selectedListen.classList.add('wrong');
            registerError(listenFeedback, 'Escucha de nuevo: menciona "team meeting" (reunión de equipo) a las 9.');
        }
    });

    // ==========================================
    // EJERCICIO 6: INGLÉS PROFESIONAL
    // ==========================================
    const proBtns = Array.from(document.querySelectorAll('#proOptions .option-card-3d'));
    const checkPro = document.getElementById('checkPro');
    const proFeedback = document.getElementById('proFeedback');
    let selectedPro = null;

    proBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            proBtns.forEach(b => b.classList.remove('selected', 'correct', 'wrong'));
            btn.classList.add('selected');
            selectedPro = btn;
        });
    });

    checkPro.addEventListener('click', () => {
        if (!selectedPro) return;
        if (selectedPro.getAttribute('data-correct') === 'true') {
            selectedPro.classList.add('correct');
            speakSentence('Lets check the project schedule for this week.');
            registerSuccess(5, proFeedback, '¡Perfecto! "Project schedule" es el cronograma del proyecto.');
        } else {
            selectedPro.classList.add('wrong');
            registerError(proFeedback, 'La palabra correcta es "schedule" (cronograma/agenda).');
        }
    });

    // Inicializar
    showExercise(0);
    updateStatsUI();
});