document.addEventListener('DOMContentLoaded', () => {
    iniciarDiagnosticoCompleto();
});

const nivelesDiagnostico = [
    {
        nivel: 'A1',
        nombre: 'Principiante (A1)',
        descripcion: 'Estás dando tus primeros pasos. Comprendes expresiones cotidianas muy básicas y términos elementales de tu especialidad.',
        recomendacion: 'Comienza desde la Unidad 01 enfocándote en vocabulario esencial, verbos base y pronunciación.'
    },
    {
        nivel: 'A2',
        nombre: 'Básico (A2)',
        descripcion: 'Puedes comunicarte en tareas sencillas y comprender instrucciones frecuentes dentro de tu área profesional.',
        recomendacion: 'Refuerza los tiempos verbales simples (pasado y futuro) y practica el glosario inicial de tu carrera.'
    },
    {
        nivel: 'B1',
        nombre: 'Intermedio (B1)',
        descripcion: 'Comprendes las ideas principales de manuales claros y puedes desenvolverte en situaciones laborales habituales.',
        recomendacion: 'Avanza hacia las lecciones de comunicación operativa, reportes técnicos y comprensión auditiva.'
    },
    {
        nivel: 'B2',
        nombre: 'Intermedio Alto (B2)',
        descripcion: 'Tienes fluidez para interactuar con colegas internacionales y comprender documentación compleja de tu especialidad.',
        recomendacion: 'Enfócate en vocabulario especializado, presentaciones ejecutivas y entrevistas laborales en inglés.'
    },
    {
        nivel: 'C1',
        nombre: 'Avanzado (C1)',
        descripcion: 'Demuestras un dominio sólido, flexible y preciso del idioma para entornos académicos y profesionales exigentes.',
        recomendacion: 'Perfecciona expresiones idiomáticas corporativas, negociación y redacción técnica avanzada.'
    }
];

const bancosPorCarrera = {
    'Sistemas y Software': [
        {
            nivelTag: 'Nivel Evaluado: A1 · Principiante',
            categoria: 'TI · Verbo To Be & Roles',
            contexto: 'Selecciona la conjugación correcta para describir un puesto técnico.',
            texto: 'She ___ a backend developer at a software company.',
            audio: 'She is a backend developer at a software company.',
            opciones: ['are', 'is', 'am', 'be'],
            correcta: 1,
            explicacion: 'Con los pronombres "He", "She" e "It" siempre se utiliza "is".'
        },
        {
            nivelTag: 'Nivel Evaluado: A1 · Principiante',
            categoria: 'TI · Presente Simple',
            contexto: 'Elige la forma verbal correcta para un equipo de desarrollo.',
            texto: 'Developers ___ clean code and unit tests every day.',
            audio: 'Developers write clean code and unit tests every day.',
            opciones: ['writes', 'writing', 'write', 'to write'],
            correcta: 2,
            explicacion: 'En presente simple con sujetos plurales ("Developers") el verbo va en su forma base.'
        },
        {
            nivelTag: 'Nivel Evaluado: A2 · Básico',
            categoria: 'TI · Pasado Simple',
            contexto: 'Completa la oración sobre una corrección realizada ayer.',
            texto: 'Yesterday, our team ___ a critical bug in the login module.',
            audio: 'Yesterday, our team fixed a critical bug in the login module.',
            opciones: ['fix', 'fixing', 'fixed', 'fixes'],
            correcta: 2,
            explicacion: '"Fixed" es el pasado simple regular del verbo "fix" (reparar/corregir).'
        },
        {
            nivelTag: 'Nivel Evaluado: A2 · Básico',
            categoria: 'TI · Comparativos',
            contexto: 'Compara el rendimiento de dos servidores.',
            texto: 'This cloud server is much ___ than our old local machine.',
            audio: 'This cloud server is much faster than our old local machine.',
            opciones: ['fast', 'more fast', 'fastest', 'faster'],
            correcta: 3,
            explicacion: 'Para adjetivos cortos como "fast", el comparativo se forma agregando "-er" (faster).'
        },
        {
            nivelTag: 'Nivel Evaluado: B1 · Intermedio',
            categoria: 'TI · Presente Perfecto',
            contexto: 'Expresa una implementación continua en un proyecto.',
            texto: 'We ___ used Git for version control since the project started.',
            audio: 'We have used Git for version control since the project started.',
            opciones: ['has', 'have', 'are', 'had been'],
            correcta: 1,
            explicacion: 'Con "We" y el marcador "since" usamos "have" + participio pasado.'
        },
        {
            nivelTag: 'Nivel Evaluado: B1 · Intermedio',
            categoria: 'Ciberseguridad · Modales',
            contexto: 'Selecciona el modal que expresa una política de seguridad obligatoria.',
            texto: 'All engineers ___ enable two-factor authentication on their accounts.',
            audio: 'All engineers must enable two-factor authentication on their accounts.',
            opciones: ['must', 'might', 'could to', 'would'],
            correcta: 0,
            explicacion: '"Must" indica obligación estricta o norma de seguridad informática.'
        },
        {
            nivelTag: 'Nivel Evaluado: B2 · Intermedio Alto',
            categoria: 'TI · Segundo Condicional',
            contexto: 'Completa la hipótesis sobre optimización de base de datos.',
            texto: 'If we indexed that table, the queries ___ twice as fast.',
            audio: 'If we indexed that table, the queries would run twice as fast.',
            opciones: ['will run', 'run', 'would run', 'would have run'],
            correcta: 2,
            explicacion: 'El segundo condicional usa: If + pasado simple, would + verbo base.'
        },
        {
            nivelTag: 'Nivel Evaluado: B2 · Intermedio Alto',
            categoria: 'DevOps · Vocabulario Técnico',
            contexto: 'Elige el significado correcto de la expresión en producción.',
            texto: 'What does “The system suffered unexpected downtime” mean?',
            audio: 'The system suffered unexpected downtime.',
            opciones: [
                'The system became faster than usual.',
                'The system was temporarily offline or unavailable.',
                'The database was permanently deleted.',
                'The users updated their passwords.'
            ],
            correcta: 1,
            explicacion: '"Downtime" se refiere al tiempo en que un servidor o servicio está fuera de línea.'
        },
        {
            nivelTag: 'Nivel Evaluado: C1 · Avanzado',
            categoria: 'Arquitectura · Voz Pasiva',
            contexto: 'Selecciona la estructura formal para un reporte de despliegue.',
            texto: 'By next month, the new microservices architecture ___ across all regions.',
            audio: 'By next month, the new microservices architecture will have been deployed across all regions.',
            opciones: [
                'will have deployed',
                'will have been deployed',
                'is deploying',
                'has been deploying'
            ],
            correcta: 1,
            explicacion: 'Se usa futuro perfecto pasivo ("will have been deployed") porque la arquitectura recibe la acción.'
        },
        {
            nivelTag: 'Nivel Evaluado: C1 · Avanzado',
            categoria: 'Ingeniería · Precisión Léxica',
            contexto: 'Elige el verbo técnico adecuado para resolver un cuello de botella.',
            texto: 'We need to ___ the latency bottleneck before releasing version 2.0.',
            audio: 'We need to address the latency bottleneck before releasing version 2.0.',
            opciones: ['address', 'ignore', 'postpone', 'hesitate'],
            correcta: 0,
            explicacion: '"Address a bottleneck/issue" significa abordar y solucionar un problema crítico.'
        }
    ],

    'Medicina y Salud': [
        {
            nivelTag: 'Nivel Evaluado: A1 · Principiante',
            categoria: 'Medicina · Verbo To Be',
            contexto: 'Selecciona la conjugación correcta para describir a un profesional de la salud.',
            texto: 'He ___ a cardiologist at the general hospital.',
            audio: 'He is a cardiologist at the general hospital.',
            opciones: ['are', 'is', 'am', 'be'],
            correcta: 1,
            explicacion: 'Con los pronombres singulares "He", "She" e "It" se usa "is".'
        },
        {
            nivelTag: 'Nivel Evaluado: A1 · Principiante',
            categoria: 'Enfermería · Presente Simple',
            contexto: 'Elige el verbo correcto para una rutina clínica.',
            texto: 'Nurses ___ the patient’s blood pressure every morning.',
            audio: 'Nurses check the patient blood pressure every morning.',
            opciones: ['checks', 'checking', 'check', 'to check'],
            correcta: 2,
            explicacion: 'Con el sujeto plural "Nurses", el verbo mantiene su forma base ("check").'
        },
        {
            nivelTag: 'Nivel Evaluado: A2 · Básico',
            categoria: 'Consulta · Pasado Simple',
            contexto: 'Completa la nota clínica sobre un paciente atendido ayer.',
            texto: 'Yesterday, the patient ___ of severe headache and fever.',
            audio: 'Yesterday, the patient complained of severe headache and fever.',
            opciones: ['complain', 'complaining', 'complained', 'complains'],
            correcta: 2,
            explicacion: '"Complained" es el pasado simple de "complain" (referir síntomas o dolor).'
        },
        {
            nivelTag: 'Nivel Evaluado: A2 · Básico',
            categoria: 'Diagnóstico · Comparativos',
            contexto: 'Compara la evolución clínica de un paciente.',
            texto: 'The patient’s recovery is much ___ today than yesterday.',
            audio: 'The patient recovery is much better today than yesterday.',
            opciones: ['good', 'more good', 'best', 'better'],
            correcta: 3,
            explicacion: '"Better" es el comparativo irregular de "good".'
        },
        {
            nivelTag: 'Nivel Evaluado: B1 · Intermedio',
            categoria: 'Historial Clínico · Presente Perfecto',
            contexto: 'Indica un tratamiento iniciado en el pasado que continúa.',
            texto: 'The patient ___ taken antibiotics for five days.',
            audio: 'The patient has taken antibiotics for five days.',
            opciones: ['has', 'have', 'is', 'was'],
            correcta: 0,
            explicacion: 'Con sujeto singular ("The patient") en presente perfecto se usa "has" + participio.'
        },
        {
            nivelTag: 'Nivel Evaluado: B1 · Intermedio',
            categoria: 'Bioseguridad · Modales',
            contexto: 'Selecciona el modal que indica un protocolo médico obligatorio.',
            texto: 'Surgeons ___ sterilize their hands before entering the operating room.',
            audio: 'Surgeons must sterilize their hands before entering the operating room.',
            opciones: ['must', 'might', 'could to', 'would'],
            correcta: 0,
            explicacion: '"Must" expresa obligación estricta en protocolos médicos.'
        },
        {
            nivelTag: 'Nivel Evaluado: B2 · Intermedio Alto',
            categoria: 'Urgencias · Segundo Condicional',
            contexto: 'Completa el razonamiento clínico hipotético.',
            texto: 'If the dosage were too high, the patient ___ adverse side effects.',
            audio: 'If the dosage were too high, the patient would experience adverse side effects.',
            opciones: ['will experience', 'experience', 'would experience', 'would have experienced'],
            correcta: 2,
            explicacion: 'El segundo condicional usa: If + pasado simple, would + verbo base.'
        },
        {
            nivelTag: 'Nivel Evaluado: B2 · Intermedio Alto',
            categoria: 'Terminología Clínica · Comprensión',
            contexto: 'Elige el significado correcto del término médico en urgencias.',
            texto: 'What does “The patient was admitted to the ICU” mean?',
            audio: 'The patient was admitted to the ICU.',
            opciones: [
                'The patient was discharged home.',
                'The patient was placed in the Intensive Care Unit.',
                'The patient refused medical treatment.',
                'The patient went to the pharmacy.'
            ],
            correcta: 1,
            explicacion: 'ICU significa "Intensive Care Unit" (Unidad de Cuidados Intensivos).'
        },
        {
            nivelTag: 'Nivel Evaluado: C1 · Avanzado',
            categoria: 'Investigación Médica · Voz Pasiva',
            contexto: 'Selecciona la estructura formal para un ensayo clínico.',
            texto: 'By the end of the trial, the new vaccine ___ on over 5,000 volunteers.',
            audio: 'By the end of the trial, the new vaccine will have been tested on over 5,000 volunteers.',
            opciones: [
                'will have tested',
                'will have been tested',
                'is testing',
                'has been testing'
            ],
            correcta: 1,
            explicacion: 'Se utiliza futuro perfecto en voz pasiva ("will have been tested").'
        },
        {
            nivelTag: 'Nivel Evaluado: C1 · Avanzado',
            categoria: 'Práctica Médica · Precisión Léxica',
            contexto: 'Elige el verbo clínico más preciso para aliviar síntomas.',
            texto: 'This medication is prescribed to ___ chronic inflammation in joints.',
            audio: 'This medication is prescribed to alleviate chronic inflammation in joints.',
            opciones: ['alleviate', 'aggravate', 'postpone', 'trigger'],
            correcta: 0,
            explicacion: '"Alleviate" significa aliviar o mitigar síntomas o dolor.'
        }
    ],

    'Derecho Corporativo': [
        {
            nivelTag: 'Nivel Evaluado: A1 · Principiante',
            categoria: 'Derecho · Verbo To Be',
            contexto: 'Selecciona la conjugación correcta para describir un cargo legal.',
            texto: 'She ___ a corporate lawyer specializing in contracts.',
            audio: 'She is a corporate lawyer specializing in contracts.',
            opciones: ['are', 'is', 'am', 'be'],
            correcta: 1,
            explicacion: 'Con "She", "He" e "It" se utiliza siempre "is".'
        },
        {
            nivelTag: 'Nivel Evaluado: A1 · Principiante',
            categoria: 'Despacho · Presente Simple',
            contexto: 'Elige el verbo correcto para una actividad habitual en el bufete.',
            texto: 'Attorneys ___ legal agreements before their clients sign them.',
            audio: 'Attorneys review legal agreements before their clients sign them.',
            opciones: ['reviews', 'reviewing', 'review', 'to review'],
            correcta: 2,
            explicacion: 'Con el sujeto plural "Attorneys", el verbo va en su forma base ("review").'
        },
        {
            nivelTag: 'Nivel Evaluado: A2 · Básico',
            categoria: 'Litigio · Pasado Simple',
            contexto: 'Completa la oración sobre una audiencia celebrada ayer.',
            texto: 'Yesterday, the judge ___ the final verdict in the courtroom.',
            audio: 'Yesterday, the judge announced the final verdict in the courtroom.',
            opciones: ['announce', 'announcing', 'announced', 'announces'],
            correcta: 2,
            explicacion: '"Announced" es el pasado simple regular del verbo "announce".'
        },
        {
            nivelTag: 'Nivel Evaluado: A2 · Básico',
            categoria: 'Contratos · Comparativos',
            contexto: 'Compara dos cláusulas contractuales.',
            texto: 'The revised contract is much ___ than the initial draft.',
            audio: 'The revised contract is much clearer than the initial draft.',
            opciones: ['clear', 'more clear', 'clearest', 'clearer'],
            correcta: 3,
            explicacion: 'Para adjetivos cortos como "clear", el comparativo es "clearer".'
        },
        {
            nivelTag: 'Nivel Evaluado: B1 · Intermedio',
            categoria: 'Cumplimiento · Presente Perfecto',
            contexto: 'Indica una vigencia legal desde una fecha específica.',
            texto: 'Both companies ___ complied with the regulation since 2024.',
            audio: 'Both companies have complied with the regulation since 2024.',
            opciones: ['has', 'have', 'are', 'was'],
            correcta: 1,
            explicacion: 'Con sujeto plural ("Both companies") en presente perfecto se usa "have".'
        },
        {
            nivelTag: 'Nivel Evaluado: B1 · Intermedio',
            categoria: 'Normativa · Verbos Modales',
            contexto: 'Selecciona el modal que expresa obligación legal vinculante.',
            texto: 'All parties ___ keep confidential information strictly private.',
            audio: 'All parties must keep confidential information strictly private.',
            opciones: ['must', 'might', 'could to', 'would'],
            correcta: 0,
            explicacion: 'En textos legales y contratos, "must" o "shall" expresan obligación vinculante.'
        },
        {
            nivelTag: 'Nivel Evaluado: B2 · Intermedio Alto',
            categoria: 'Cláusulas · Segundo Condicional',
            contexto: 'Completa la consecuencia hipotética de un incumplimiento.',
            texto: 'If either party breached the agreement, the contract ___ terminated immediately.',
            audio: 'If either party breached the agreement, the contract would be terminated immediately.',
            opciones: ['will be', 'is', 'would be', 'would have been'],
            correcta: 2,
            explicacion: 'El segundo condicional sigue la estructura: If + pasado simple, would + verbo base.'
        },
        {
            nivelTag: 'Nivel Evaluado: B2 · Intermedio Alto',
            categoria: 'Vocabulario Jurídico · Comprensión',
            contexto: 'Elige el significado correcto del término legal.',
            texto: 'What does “The defendant signed a non-disclosure agreement (NDA)” mean?',
            audio: 'The defendant signed a non-disclosure agreement.',
            opciones: [
                'They agreed to share secrets publicly.',
                'They legally agreed not to reveal confidential information.',
                'They cancelled the lawsuit.',
                'They hired a new attorney.'
            ],
            correcta: 1,
            explicacion: 'Un NDA ("Non-Disclosure Agreement") es un contrato de confidencialidad.'
        },
        {
            nivelTag: 'Nivel Evaluado: C1 · Avanzado',
            categoria: 'Derecho Mercantil · Voz Pasiva',
            contexto: 'Selecciona la estructura formal para un cierre de fusión.',
            texto: 'By next Friday, the merger documents ___ by the board of directors.',
            audio: 'By next Friday, the merger documents will have been ratified by the board of directors.',
            opciones: [
                'will have ratified',
                'will have been ratified',
                'are ratifying',
                'have been ratifying'
            ],
            correcta: 1,
            explicacion: 'Se usa futuro perfecto pasivo ("will have been ratified") porque los documentos reciben la acción.'
        },
        {
            nivelTag: 'Nivel Evaluado: C1 · Avanzado',
            categoria: 'Litigio Internacional · Precisión Léxica',
            contexto: 'Elige el término jurídico adecuado para anular una cláusula.',
            texto: 'The supreme court decided to ___ the unconstitutional clause.',
            audio: 'The supreme court decided to nullify the unconstitutional clause.',
            opciones: ['nullify', 'enforce', 'postpone', 'draft'],
            correcta: 0,
            explicacion: '"Nullify" significa invalidar o anular legalmente una norma o cláusula.'
        }
    ],

    'Gastronomía': [
        {
            nivelTag: 'Nivel Evaluado: A1 · Principiante',
            categoria: 'Cocina · Verbo To Be',
            contexto: 'Selecciona la conjugación correcta para presentar a un miembro de la brigada.',
            texto: 'He ___ the executive chef at a five-star restaurant.',
            audio: 'He is the executive chef at a five-star restaurant.',
            opciones: ['are', 'is', 'am', 'be'],
            correcta: 1,
            explicacion: 'Con "He", "She" e "It" se utiliza "is".'
        },
        {
            nivelTag: 'Nivel Evaluado: A1 · Principiante',
            categoria: 'Preparación · Presente Simple',
            contexto: 'Elige el verbo correcto para una rutina diaria en cocina.',
            texto: 'Chefs ___ fresh vegetables before the restaurant opens.',
            audio: 'Chefs chop fresh vegetables before the restaurant opens.',
            opciones: ['chops', 'chopping', 'chop', 'to chop'],
            correcta: 2,
            explicacion: 'Con el sujeto plural "Chefs" el verbo va en su forma base ("chop" = picar).'
        },
        {
            nivelTag: 'Nivel Evaluado: A2 · Básico',
            categoria: 'Servicio · Pasado Simple',
            contexto: 'Completa la oración sobre un banquete servido ayer.',
            texto: 'Yesterday, our kitchen team ___ over two hundred guests.',
            audio: 'Yesterday, our kitchen team served over two hundred guests.',
            opciones: ['serve', 'serving', 'served', 'serves'],
            correcta: 2,
            explicacion: '"Served" es el pasado simple del verbo "serve".'
        },
        {
            nivelTag: 'Nivel Evaluado: A2 · Básico',
            categoria: 'Sabores · Comparativos',
            contexto: 'Compara dos salsas de la carta.',
            texto: 'This reduction sauce is much ___ than the traditional gravy.',
            audio: 'This reduction sauce is much richer than the traditional gravy.',
            opciones: ['rich', 'more rich', 'richest', 'richer'],
            correcta: 3,
            explicacion: 'Para adjetivos cortos como "rich", el comparativo se forma con "-er" (richer).'
        },
        {
            nivelTag: 'Nivel Evaluado: B1 · Intermedio',
            categoria: 'Repostería · Presente Perfecto',
            contexto: 'Indica una acción de horneado recién terminada.',
            texto: 'The pastry chef ___ just baked the sourdough bread.',
            audio: 'The pastry chef has just baked the sourdough bread.',
            opciones: ['has', 'have', 'is', 'was'],
            correcta: 0,
            explicacion: 'Con sujeto singular ("The pastry chef") se utiliza "has".'
        },
        {
            nivelTag: 'Nivel Evaluado: B1 · Intermedio',
            categoria: 'Higiene · Verbos Modales',
            contexto: 'Selecciona el modal que indica una norma sanitaria obligatoria.',
            texto: 'Kitchen staff ___ store raw meat below cooked food in the refrigerator.',
            audio: 'Kitchen staff must store raw meat below cooked food in the refrigerator.',
            opciones: ['must', 'might', 'could to', 'would'],
            correcta: 0,
            explicacion: '"Must" expresa obligación sanitaria para evitar contaminación cruzada.'
        },
        {
            nivelTag: 'Nivel Evaluado: B2 · Intermedio Alto',
            categoria: 'Técnicas · Segundo Condicional',
            contexto: 'Completa la hipótesis culinaria.',
            texto: 'If you seared the steak at a higher temperature, it ___ a better crust.',
            audio: 'If you seared the steak at a higher temperature, it would develop a better crust.',
            opciones: ['will develop', 'develops', 'would develop', 'would have developed'],
            correcta: 2,
            explicacion: 'Segundo condicional: If + pasado simple, would + verbo base.'
        },
        {
            nivelTag: 'Nivel Evaluado: B2 · Intermedio Alto',
            categoria: 'Vocabulario Culinario · Comprensión',
            contexto: 'Elige el significado correcto de la técnica en cocina.',
            texto: 'What does “Simmer the broth for twenty minutes” mean?',
            audio: 'Simmer the broth for twenty minutes.',
            opciones: [
                'Freeze the broth immediately.',
                'Cook the broth gently just below boiling point.',
                'Fry the vegetables in hot oil.',
                'Serve the soup cold.'
            ],
            correcta: 1,
            explicacion: '"Simmer" significa cocer a fuego lento justo por debajo del punto de ebullición.'
        },
        {
            nivelTag: 'Nivel Evaluado: C1 · Avanzado',
            categoria: 'Alta Cocina · Voz Pasiva',
            contexto: 'Selecciona la estructura formal para un menú degustación.',
            texto: 'By 8:00 PM, all seven courses ___ by our sommelier and chef.',
            audio: 'By 8 PM, all seven courses will have been paired by our sommelier and chef.',
            opciones: [
                'will have paired',
                'will have been paired',
                'are pairing',
                'have been pairing'
            ],
            correcta: 1,
            explicacion: 'Futuro perfecto en voz pasiva ("will have been paired" = habrán sido maridados).'
        },
        {
            nivelTag: 'Nivel Evaluado: C1 · Avanzado',
            categoria: 'Gestión Gastronómica · Precisión Léxica',
            contexto: 'Elige el término preciso para resaltar sabores en un platillo.',
            texto: 'A touch of citrus acidity helps to ___ the richness of the dish.',
            audio: 'A touch of citrus acidity helps to balance the richness of the dish.',
            opciones: ['balance', 'spoil', 'overcook', 'dilute'],
            correcta: 0,
            explicacion: '"Balance" significa equilibrar los sabores de una preparación.'
        }
    ],

    'Arquitectura': [
        {
            nivelTag: 'Nivel Evaluado: A1 · Principiante',
            categoria: 'Arquitectura · Verbo To Be',
            contexto: 'Selecciona la conjugación correcta para describir a un profesionista.',
            texto: 'She ___ an urban architect working on sustainable housing.',
            audio: 'She is an urban architect working on sustainable housing.',
            opciones: ['are', 'is', 'am', 'be'],
            correcta: 1,
            explicacion: 'Con "She", "He" e "It" se utiliza "is".'
        },
        {
            nivelTag: 'Nivel Evaluado: A1 · Principiante',
            categoria: 'Estudio · Presente Simple',
            contexto: 'Elige el verbo correcto para el trabajo diario en despacho.',
            texto: 'Architects ___ 3D models and floor plans using CAD software.',
            audio: 'Architects design 3D models and floor plans using CAD software.',
            opciones: ['designs', 'designing', 'design', 'to design'],
            correcta: 2,
            explicacion: 'Con el sujeto plural "Architects" el verbo mantiene su forma base ("design").'
        },
        {
            nivelTag: 'Nivel Evaluado: A2 · Básico',
            categoria: 'Obra · Pasado Simple',
            contexto: 'Completa la oración sobre una inspección realizada ayer.',
            texto: 'Yesterday, the structural engineer ___ the concrete foundation.',
            audio: 'Yesterday, the structural engineer inspected the concrete foundation.',
            opciones: ['inspect', 'inspecting', 'inspected', 'inspects'],
            correcta: 2,
            explicacion: '"Inspected" es el pasado simple regular de "inspect".'
        },
        {
            nivelTag: 'Nivel Evaluado: A2 · Básico',
            categoria: 'Materiales · Comparativos',
            contexto: 'Compara la resistencia de dos materiales estructurales.',
            texto: 'Reinforced steel is much ___ than standard timber.',
            audio: 'Reinforced steel is much stronger than standard timber.',
            opciones: ['strong', 'more strong', 'strongest', 'stronger'],
            correcta: 3,
            explicacion: 'Para adjetivos cortos como "strong", el comparativo es "stronger".'
        },
        {
            nivelTag: 'Nivel Evaluado: B1 · Intermedio',
            categoria: 'Construcción · Presente Perfecto',
            contexto: 'Indica el avance continuo de una edificación.',
            texto: 'The contractors ___ built three floors since last month.',
            audio: 'The contractors have built three floors since last month.',
            opciones: ['has', 'have', 'are', 'was'],
            correcta: 1,
            explicacion: 'Con sujeto plural ("The contractors") en presente perfecto se usa "have".'
        },
        {
            nivelTag: 'Nivel Evaluado: B1 · Intermedio',
            categoria: 'Seguridad en Obra · Modales',
            contexto: 'Selecciona el modal que expresa una norma obligatoria en construcción.',
            texto: 'Visitors ___ wear a hard hat and safety boots on the construction site.',
            audio: 'Visitors must wear a hard hat and safety boots on the construction site.',
            opciones: ['must', 'might', 'could to', 'would'],
            correcta: 0,
            explicacion: '"Must" expresa obligación estricta de seguridad en obra.'
        },
        {
            nivelTag: 'Nivel Evaluado: B2 · Intermedio Alto',
            categoria: 'Diseño Bioclimático · Segundo Condicional',
            contexto: 'Completa la hipótesis de eficiencia energética.',
            texto: 'If the building had larger south-facing windows, it ___ more natural light.',
            audio: 'If the building had larger south-facing windows, it would receive more natural light.',
            opciones: ['will receive', 'receives', 'would receive', 'would have received'],
            correcta: 2,
            explicacion: 'Segundo condicional: If + pasado simple, would + verbo base.'
        },
        {
            nivelTag: 'Nivel Evaluado: B2 · Intermedio Alto',
            categoria: 'Vocabulario Estructural · Comprensión',
            contexto: 'Elige el significado correcto del término arquitectónico.',
            texto: 'What does “Do not demolish that load-bearing wall” mean?',
            audio: 'Do not demolish that load-bearing wall.',
            opciones: [
                'The wall is only decorative.',
                'The wall supports the weight of the structure above it.',
                'The wall needs a fresh coat of paint.',
                'The wall is made of glass.'
            ],
            correcta: 1,
            explicacion: '"Load-bearing wall" es un muro de carga que soporta peso estructural.'
        },
        {
            nivelTag: 'Nivel Evaluado: C1 · Avanzado',
            categoria: 'Urbanismo · Voz Pasiva',
            contexto: 'Selecciona la estructura formal para un proyecto público.',
            texto: 'By 2027, the new cultural center ___ using zero-emission materials.',
            audio: 'By 2027, the new cultural center will have been constructed using zero-emission materials.',
            opciones: [
                'will have constructed',
                'will have been constructed',
                'is constructing',
                'has been constructing'
            ],
            correcta: 1,
            explicacion: 'Futuro perfecto en voz pasiva ("will have been constructed").'
        },
        {
            nivelTag: 'Nivel Evaluado: C1 · Avanzado',
            categoria: 'Restauración · Precisión Léxica',
            contexto: 'Elige el verbo técnico adecuado para rehabilitar un edificio histórico.',
            texto: 'The project aims to ___ the historic facade while modernizing the interior.',
            audio: 'The project aims to preserve the historic facade while modernizing the interior.',
            opciones: ['preserve', 'demolish', 'neglect', 'obstruct'],
            correcta: 0,
            explicacion: '"Preserve" significa conservar y proteger el patrimonio arquitectónico.'
        }
    ],

    'General': [
        {
            nivelTag: 'Nivel Evaluado: A1 · Principiante',
            categoria: 'Gramática · Verbo To Be',
            contexto: 'Selecciona la conjugación correcta para la tercera persona del singular.',
            texto: 'She ___ a university student in Pachuca.',
            audio: 'She is a university student in Pachuca.',
            opciones: ['are', 'is', 'am', 'be'],
            correcta: 1,
            explicacion: 'Con los pronombres "He", "She" e "It" siempre se utiliza "is".'
        },
        {
            nivelTag: 'Nivel Evaluado: A1 · Principiante',
            categoria: 'Presente Simple · Rutinas',
            contexto: 'Elige la forma verbal correcta para un sujeto plural.',
            texto: 'They ___ English every afternoon.',
            audio: 'They study English every afternoon.',
            opciones: ['studies', 'studying', 'study', 'to study'],
            correcta: 2,
            explicacion: 'En presente simple con "They" el verbo mantiene su forma base.'
        },
        {
            nivelTag: 'Nivel Evaluado: A2 · Básico',
            categoria: 'Pasado Simple · Verbos Irregulares',
            contexto: 'Completa la oración indicando una acción terminada ayer.',
            texto: 'Yesterday, we ___ to the science museum.',
            audio: 'Yesterday, we went to the science museum.',
            opciones: ['go', 'gone', 'went', 'going'],
            correcta: 2,
            explicacion: '"Went" es el pasado simple irregular del verbo "go".'
        },
        {
            nivelTag: 'Nivel Evaluado: A2 · Básico',
            categoria: 'Comparativos · Vida Diaria',
            contexto: 'Compara dos medios de transporte.',
            texto: 'Taking the train is much ___ than driving in heavy traffic.',
            audio: 'Taking the train is much faster than driving in heavy traffic.',
            opciones: ['fast', 'more fast', 'fastest', 'faster'],
            correcta: 3,
            explicacion: 'Para adjetivos cortos como "fast", el comparativo es "faster".'
        },
        {
            nivelTag: 'Nivel Evaluado: B1 · Intermedio',
            categoria: 'Presente Perfecto · Experiencia',
            contexto: 'Expresa una acción que comenzó en el pasado y continúa vigente.',
            texto: 'I ___ lived in this city since 2020.',
            audio: 'I have lived in this city since 2020.',
            opciones: ['has', 'have', 'am', 'was'],
            correcta: 1,
            explicacion: 'Con "I" y "since" se utiliza "have" + participio pasado.'
        },
        {
            nivelTag: 'Nivel Evaluado: B1 · Intermedio',
            categoria: 'Modales · Obligación',
            contexto: 'Selecciona el verbo modal que indica norma obligatoria.',
            texto: 'Passengers ___ fasten their seatbelts during takeoff.',
            audio: 'Passengers must fasten their seatbelts during takeoff.',
            opciones: ['must', 'might', 'could to', 'would'],
            correcta: 0,
            explicacion: '"Must" expresa obligación estricta.'
        },
        {
            nivelTag: 'Nivel Evaluado: B2 · Intermedio Alto',
            categoria: 'Segundo Condicional · Hipótesis',
            contexto: 'Completa la situación hipotética en el presente.',
            texto: 'If I had more free time, I ___ another language.',
            audio: 'If I had more free time, I would learn another language.',
            opciones: ['will learn', 'learn', 'would learn', 'would have learned'],
            correcta: 2,
            explicacion: 'El segundo condicional sigue la estructura: If + pasado simple, would + verbo base.'
        },
        {
            nivelTag: 'Nivel Evaluado: B2 · Intermedio Alto',
            categoria: 'Comprensión Lectora · Expresiones',
            contexto: 'Elige la frase con el significado más cercano.',
            texto: 'What does “The conference was postponed until next week” mean?',
            audio: 'The conference was postponed until next week.',
            opciones: [
                'The conference was cancelled forever.',
                'The conference was rescheduled to a later date.',
                'The conference started earlier.',
                'Nobody attended the conference.'
            ],
            correcta: 1,
            explicacion: '"Postponed" significa posponer o reprogramar un evento.'
        },
        {
            nivelTag: 'Nivel Evaluado: C1 · Avanzado',
            categoria: 'Voz Pasiva Formal · Reportes',
            contexto: 'Selecciona la estructura formal adecuada.',
            texto: 'By next year, the new international airport ___ completely.',
            audio: 'By next year, the new international airport will have been expanded completely.',
            opciones: [
                'will have expanded',
                'will have been expanded',
                'is expanding',
                'has been expanding'
            ],
            correcta: 1,
            explicacion: 'Futuro perfecto en voz pasiva ("will have been expanded").'
        },
        {
            nivelTag: 'Nivel Evaluado: C1 · Avanzado',
            categoria: 'Precisión Léxica · Comunicación',
            contexto: 'Elige el verbo más preciso en un contexto profesional.',
            texto: 'We must ___ the root cause of the problem before making a decision.',
            audio: 'We must identify the root cause of the problem before making a decision.',
            opciones: ['identify', 'ignore', 'postpone', 'hesitate'],
            correcta: 0,
            explicacion: '"Identify the root cause" significa identificar la causa raíz.'
        }
    ]
};

function speakQuestion(text) {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        const u = new SpeechSynthesisUtterance(text);
        u.lang = 'en-US';
        u.rate = 0.92;
        window.speechSynthesis.speak(u);
    }
}

function iniciarDiagnosticoCompleto() {
    const btnIniciar = document.getElementById('btnIniciarExamen');
    if (!btnIniciar) return;

    // Secciones
    const pantallaInicio = document.getElementById('pantalla-inicio');
    const pantallaExamen = document.getElementById('pantalla-examen');
    const pantallaResultados = document.getElementById('pantalla-resultados');

    // Barras de navegación
    const normalNavbar = document.getElementById('normalNavbar');
    const normalFooter = document.getElementById('normalFooter');
    const examLockHeader = document.getElementById('examLockHeader');

    // Controles de examen
    const formulario = document.getElementById('diagnostico-form');
    const nivelBadge = document.getElementById('nivel-pregunta-badge');
    const categoriaBadge = document.getElementById('pregunta-categoria');
    const textoPregunta = document.getElementById('pregunta-texto');
    const contextoPregunta = document.getElementById('pregunta-contexto');
    const btnAudio = document.getElementById('btnEscucharPregunta');
    const progresoTexto = document.getElementById('progreso-texto');
    const barraProgreso = document.getElementById('barra-progreso');
    const botonesOpcion = [...document.querySelectorAll('.opcion-respuesta')];
    const btnAnterior = document.getElementById('anterior');
    const btnSiguiente = document.getElementById('siguiente');
    const mensajeError = document.getElementById('mensaje-error');

    // Estado del examen
    let carreraElegida = 'Sistemas y Software';
    let preguntasActivas = bancosPorCarrera[carreraElegida];
    let examenActivo = false;
    let indice = 0;
    let salidasDetectadas = 0;
    let tiempoRestante = 600; // 10 minutos
    let timerInterval = null;
    let respuestas = Array(10).fill(null);

    // Selector de carrera en pantalla inicial
    document.querySelectorAll('.career-select-pill').forEach(pill => {
        pill.addEventListener('click', () => {
            document.querySelectorAll('.career-select-pill').forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            carreraElegida = pill.getAttribute('data-career');
            preguntasActivas = bancosPorCarrera[carreraElegida] || bancosPorCarrera['General'];
        });
    });

    // ==========================================
    // SEGURIDAD: BLOQUEO DE SALIDA DURANTE EXAMEN
    // ==========================================
    const modalSalida = new bootstrap.Modal(document.getElementById('modalSalidaExamen'));

    function handleBeforeUnload(e) {
        if (!examenActivo) return;
        e.preventDefault();
        e.returnValue = 'Tienes un examen diagnóstico en curso. ¿Seguro que deseas salir?';
        return e.returnValue;
    }

    function registrarIntentoSalida() {
        if (!examenActivo) return;
        salidasDetectadas++;
        document.getElementById('tabLeaveCount').textContent = salidasDetectadas;

        if (salidasDetectadas >= 3) {
            finalizarYCalcularResultados(true);
            return;
        }

        document.getElementById('textoAdvertenciaSalida').innerHTML =
            `Detectamos que saliste de la interfaz (<strong>Advertencia ${salidasDetectadas} de 3</strong>).`;
        modalSalida.show();
    }

    function activarModoBloqueo() {
        examenActivo = true;
        document.body.classList.add('exam-locked');
        normalNavbar.classList.add('d-none');
        normalFooter.classList.add('d-none');
        examLockHeader.classList.remove('d-none');

        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen().catch(() => {});
        }

        history.pushState(null, null, location.href);
        window.addEventListener('popstate', () => {
            if (examenActivo) {
                history.pushState(null, null, location.href);
                registrarIntentoSalida();
            }
        });

        window.addEventListener('beforeunload', handleBeforeUnload);

        document.addEventListener('visibilitychange', () => {
            if (document.hidden && examenActivo) {
                registrarIntentoSalida();
            }
        });

        document.addEventListener('contextmenu', e => {
            if (examenActivo) e.preventDefault();
        });

        timerInterval = setInterval(() => {
            tiempoRestante--;
            const min = String(Math.floor(tiempoRestante / 60)).padStart(2, '0');
            const seg = String(tiempoRestante % 60).padStart(2, '0');
            document.getElementById('examTimer').textContent = `${min}:${seg}`;

            if (tiempoRestante <= 0) {
                finalizarYCalcularResultados(true);
            }
        }, 1000);
    }

    function desactivarModoBloqueo() {
        examenActivo = false;
        clearInterval(timerInterval);
        document.body.classList.remove('exam-locked');
        window.removeEventListener('beforeunload', handleBeforeUnload);
        examLockHeader.classList.add('d-none');
        normalNavbar.classList.remove('d-none');
        normalFooter.classList.remove('d-none');

        if (document.fullscreenElement && document.exitFullscreen) {
            document.exitFullscreen().catch(() => {});
        }
    }

    // ==========================================
    // RENDERIZADO DE PREGUNTAS
    // ==========================================
    function renderizarPregunta() {
        const q = preguntasActivas[indice];
        nivelBadge.textContent = `${q.nivelTag} · ${carreraElegida}`;
        categoriaBadge.textContent = q.categoria;
        textoPregunta.textContent = q.texto;
        contextoPregunta.textContent = q.contexto;
        progresoTexto.textContent = `Pregunta ${indice + 1} de ${preguntasActivas.length}`;

        const pct = Math.round(((indice + 1) / preguntasActivas.length) * 100);
        barraProgreso.style.width = `${pct}%`;

        btnAudio.onclick = () => speakQuestion(q.audio);

        botonesOpcion.forEach((btn, idx) => {
            const label = btn.querySelector('.option-label');
            label.textContent = q.opciones[idx];
            btn.classList.toggle('seleccionada', respuestas[indice] === idx);
        });

        btnAnterior.disabled = indice === 0;
        btnSiguiente.innerHTML = indice === preguntasActivas.length - 1
            ? 'Finalizar Examen <i class="bi bi-check-circle-fill ms-1"></i>'
            : 'Siguiente <i class="bi bi-arrow-right ms-1"></i>';

        mensajeError.classList.add('d-none');
    }

    botonesOpcion.forEach(btn => {
        btn.addEventListener('click', () => {
            respuestas[indice] = Number(btn.getAttribute('data-option'));
            renderizarPregunta();
        });
    });

    btnAnterior.addEventListener('click', () => {
        if (indice > 0) {
            indice--;
            renderizarPregunta();
        }
    });

    formulario.addEventListener('submit', e => {
        e.preventDefault();
        if (respuestas[indice] === null) {
            mensajeError.classList.remove('d-none');
            return;
        }

        if (indice < preguntasActivas.length - 1) {
            indice++;
            renderizarPregunta();
        } else {
            finalizarYCalcularResultados(false);
        }
    });

    // ==========================================
    // CÁLCULO DE NIVEL CEFR Y PANTALLA FINAL
    // ==========================================
    function finalizarYCalcularResultados(porTiempoOSalida) {
        desactivarModoBloqueo();
        modalSalida.hide();

        let aciertos = 0;
        const revision = preguntasActivas.map((q, idx) => {
            const elegidaIdx = respuestas[idx];
            const esCorrecta = elegidaIdx === q.correcta;
            if (esCorrecta) aciertos++;

            return {
                pregunta: q.texto,
                respuestaElegida: elegidaIdx !== null ? q.opciones[elegidaIdx] : 'Sin responder',
                respuestaCorrecta: q.opciones[q.correcta],
                explicacion: q.explicacion,
                correcta: esCorrecta
            };
        });

        let indiceNivel = 0;
        if (aciertos >= 9) indiceNivel = 4;      // C1
        else if (aciertos >= 7) indiceNivel = 3; // B2
        else if (aciertos >= 5) indiceNivel = 2; // B1
        else if (aciertos >= 3) indiceNivel = 1; // A2
        else indiceNivel = 0;                    // A1

        const infoNivel = nivelesDiagnostico[indiceNivel];
        const resultado = {
            ...infoNivel,
            aciertos,
            total: preguntasActivas.length,
            carrera: carreraElegida,
            revision
        };

        try {
            localStorage.setItem('shiftlyDiagnosticoResultado', JSON.stringify(resultado));
        } catch (_) {}

        pantallaExamen.classList.add('d-none');
        pantallaResultados.classList.remove('d-none');

        document.getElementById('resultado-nivel-codigo').textContent = infoNivel.nivel;
        document.getElementById('resultado-nivel-nombre').textContent = infoNivel.nombre;
        document.getElementById('resultado-puntaje-texto').textContent =
            `Obtuviste ${aciertos} de ${preguntasActivas.length} aciertos (${aciertos * 10}%) · Área: ${carreraElegida}${porTiempoOSalida ? ' · Examen cerrado automáticamente' : ''}`;
        document.getElementById('resultado-descripcion').textContent = infoNivel.descripcion;
        document.getElementById('resultado-carrera').textContent = carreraElegida;
        document.getElementById('resultado-recomendacion').textContent =
            `${infoNivel.recomendacion} Te recomendamos iniciar con el glosario y ejercicios especializados de ${carreraElegida}.`;

        const listaRevision = document.getElementById('lista-revision');
        listaRevision.innerHTML = '';

        revision.forEach((item, i) => {
            const div = document.createElement('div');
            div.className = 'p-3 rounded-4';
            div.style.backgroundColor = item.correcta ? '#F0FDF4' : '#FEF2F2';
            div.style.border = item.correcta ? '2px solid #86EFAC' : '2px solid #FECACA';

            div.innerHTML = `
                <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                    <strong class="small" style="color: var(--purple-dark);">Pregunta ${i + 1}: ${item.pregunta}</strong>
                    <span class="badge rounded-pill ${item.correcta ? 'bg-success' : 'bg-danger'}">
                        ${item.correcta ? 'Correcta' : 'Incorrecta'}
                    </span>
                </div>
                <div class="small mb-1">
                    <strong>Tu respuesta:</strong> ${item.respuestaElegida}
                    ${!item.correcta ? ` · <strong class="text-success">Correcta:</strong> ${item.respuestaCorrecta}` : ''}
                </div>
                <div class="small text-muted"><i class="bi bi-info-circle-fill me-1"></i>${item.explicacion}</div>
            `;
            listaRevision.appendChild(div);
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Iniciar examen con el banco de la carrera elegida
    btnIniciar.addEventListener('click', () => {
        preguntasActivas = bancosPorCarrera[carreraElegida] || bancosPorCarrera['General'];
        respuestas = Array(preguntasActivas.length).fill(null);
        indice = 0;

        pantallaInicio.classList.add('d-none');
        pantallaExamen.classList.remove('d-none');
        activarModoBloqueo();
        renderizarPregunta();
    });
}