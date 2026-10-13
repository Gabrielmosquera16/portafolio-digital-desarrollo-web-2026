# Plan — Portafolio Digital Estudiantil

## Objetivo y alcance del preliminar

Crear una aplicación de portafolio académico para Gabriel Oriel Mosquera Magallón que corra localmente en XAMPP con PHP 8, HTML5, CSS y MySQL/MariaDB. El repositorio GitHub será público y contendrá una versión sanitizada del código y los materiales que no exponen datos personales sensibles. El enlace GitHub mostrará el código; no ejecuta PHP ni MySQL.

El portafolio seguirá la organización indicada por el profesor: portada, descripción del curso, presentación general, actividades/asignaciones con enunciado y solución, materiales didácticos y conclusión final. Solo se cargarán actividades y conclusiones que el estudiante realmente proporcione; el preliminar mostrará espacios pendientes en vez de inventar trabajos o reflexiones.

## Implementación

- HTML5 semántico en vistas PHP y CSS externo responsive; sin frameworks ni dependencias de Composer.
- PHP orientado a objetos con `Database`, `PortfolioRepository`, `Security` y `FileStorage`.
- MySQL/MariaDB con tablas para perfil, intereses, secciones, entradas, archivos adjuntos y administradores. Las páginas públicas consultan solamente contenido publicado.
- CRUD de entradas en un panel administrativo protegido por sesión, contraseña con `password_hash`, token CSRF y consultas PDO preparadas. Los archivos de tareas se limitan a PDF, con tamaño máximo y nombres aleatorios; se guardan fuera de la raíz web y se descargan mediante un controlador que valida la ruta.
- Instalación inicial del administrador limitada a `localhost`, con contraseña creada por el usuario; no se incluyen credenciales predeterminadas ni contraseñas en el repositorio.
- Preparación para XAMPP mediante `database/schema.sql`, `database/seed.sql`, `config/database.example.php` y una guía paso a paso. `config/database.php` y documentos agregados por el usuario no se versionan.
- Paquete público sanitizado: no incluir la cédula de la portada original ni la presentación del curso que expone contactos del docente. Sí incluir el programa oficial de la asignatura y la foto que ya forma parte de la presentación personal entregada.

## Estructura prevista

```text
.
├── app/                         # Clases PHP, seguridad, consultas y almacenamiento
├── config/                      # Ejemplo de conexión; credenciales locales excluidas
├── database/                    # Esquema MySQL y datos iniciales
├── docs/                        # Guía de arquitectura y uso
├── documentos/presentacion-general/ # Fuente Typst y PDF académico de una página
├── public/                      # Punto de entrada y vistas PHP servidas por Apache
│   ├── admin/                   # Configuración inicial, login y CRUD protegido
│   └── assets/                  # CSS e imagen personal
└── storage/portafolio/          # Árbol de documentos académico fuera de la raíz pública
```

La carpeta `storage/portafolio/` conservará subcarpetas para portada (la aplicación), descripción del curso, presentación, tareas, trabajos en clase, proyectos, prácticas, laboratorios/talleres, tres parciales según la presentación oficial del curso, otros, actividades adicionales, material del docente, material adicional, bibliografía, material UTP y conclusión final.

## Dirección de diseño

- **Movimiento:** modernismo editorial suizo, con una sobriedad visual inspirada en tableros de operaciones tecnológicos.
- **Principios:** (1) orden académico por encima del adorno; (2) legibilidad y accesibilidad; (3) seguridad y privacidad desde el inicio; (4) datos y documentos reales, sin contenido inventado.
- **Filosofía del color:** azul tinta para rigor y confianza, marfil frío para lectura cómoda, verde azulado como señal tecnológica y ámbar para destacar estados sin depender solo del color.
- **Paradigma de layout:** portada editorial asimétrica, índice lateral o numeración visible y secciones documentales que se abren en bloques verticales; tarjetas solo donde ayuden a encontrar materiales.
- **Elementos distintivos:** monograma “GM” geométrico; numeración breve por sección; etiquetas de estado “Publicado / Borrador”.
- **Interacción:** controles claros, formularios cortos y feedback inmediato; navegación por teclado con foco visible; CRUD solo para sesión autenticada.
- **Animación:** transiciones discretas de 150–200 ms para hover/foco; sin reproducción automática; respetar `prefers-reduced-motion`.
- **Tipografía:** sistema sans-serif para lectura y tablas, títulos de peso alto con jerarquía amplia; una serif de sistema solo como acento editorial; sin cargas externas.
- **Esencia de marca:** “Un registro claro y seguro del aprendizaje en desarrollo web.” Personalidad: metódica, curiosa y confiable.
- **Voz:** primera persona, profesional y directa. Ejemplos: “Aquí organizo el curso y los materiales de referencia.” “Cada actividad conservará su enunciado y su solución.”
- **Wordmark:** “GM” en un marco abierto de esquinas, acompañado por el nombre completo; construido en HTML/CSS.
- **Color distintivo:** verde azulado `#0B8587`, reservado para enlaces y acciones principales.

## Datos confirmados y pendientes

- Curso: Desarrollo Web, código 1269, sección 1S3221, II semestre de 2026, tres parciales, 3 créditos, 2 horas de teoría y 3 de laboratorio, Licenciatura en Ciberseguridad, Universidad Tecnológica de Panamá.
- Facilitador: Giovani Antonio Sánchez Gómez. Se incluye solo su nombre profesional; no se publican correo ni teléfono de la presentación del curso.
- Perfil: licenciado en Sistemas Informáticos (UTP), estudiante de Ciberseguridad y jefe de soporte del Centro de Operaciones Nacionales de Panamá.
- Intereses: caminar al aire libre, meditación, lectura y ajedrez.
- Expectativa: ampliar conocimientos, aprovechar la experiencia del profesor y pulir habilidades, con énfasis en construir aplicaciones web dinámicas y seguras.
- El portafolio recibido aún no contiene asignaciones ni conclusión final. La guía también solicita un PDF de planificación semestral que no aparece en los archivos entregados; se dejará un indicador pendiente para añadirlo cuando Gabriel lo tenga.
