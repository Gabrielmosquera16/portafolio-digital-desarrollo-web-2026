-- Datos iniciales del preliminar. No contiene cédula ni contactos del docente.
USE portafolio_digital;

INSERT INTO perfil_estudiante
  (id, nombre_completo, formacion, estudios_actuales, cargo, organizacion, biografia, expectativas)
VALUES
  (1,
   'Gabriel Oriel Mosquera Magallón',
   'Licenciado en Sistemas Informáticos, graduado de la Universidad Tecnológica de Panamá',
   'Licenciatura en Ciberseguridad',
   'Jefe de soporte',
   'Centro de Operaciones Nacionales de Panamá',
   'Soy licenciado en Sistemas Informáticos, graduado de la Universidad Tecnológica de Panamá. Actualmente estudio la Licenciatura en Ciberseguridad y trabajo como jefe de soporte en el Centro de Operaciones Nacionales de Panamá.',
   'Espero ampliar mis conocimientos, aprovechar la experiencia del profesor y perfeccionar las habilidades que ya tengo. Me interesa fortalecer mi capacidad para diseñar y desarrollar aplicaciones web dinámicas y seguras.')
ON DUPLICATE KEY UPDATE
  nombre_completo = VALUES(nombre_completo), formacion = VALUES(formacion), estudios_actuales = VALUES(estudios_actuales),
  cargo = VALUES(cargo), organizacion = VALUES(organizacion), biografia = VALUES(biografia), expectativas = VALUES(expectativas);

INSERT INTO intereses (id, nombre, descripcion, orden) VALUES
  (1, 'Caminar al aire libre', 'Disfrutar el movimiento y los espacios abiertos.', 1),
  (2, 'Meditación', 'Reservar momentos para la calma y la concentración.', 2),
  (3, 'Lectura', 'Explorar ideas y ampliar perspectivas.', 3),
  (4, 'Ajedrez', 'Practicar estrategia, análisis y paciencia.', 4)
ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion), orden = VALUES(orden);

INSERT INTO secciones (id, parent_id, slug, nombre, descripcion, orden) VALUES
  (1, NULL, 'descripcion-del-curso', 'Descripción del curso', 'Programa de asignatura y planificación del semestre.', 1),
  (2, NULL, 'presentacion-general', 'Presentación general', 'Perfil personal, hobbies y expectativas de aprendizaje.', 2),
  (3, NULL, 'actividades-asignaciones', 'Actividades y asignaciones', 'Evidencias organizadas por tipo de actividad.', 3),
  (4, 3, 'tareas-asignadas', 'Tareas asignadas', 'Cada tarea debe conservar su enunciado original y solución.', 1),
  (5, 3, 'trabajos-en-clase', 'Trabajos en clase', 'Ejercicios y trabajos desarrollados durante las clases.', 2),
  (6, 3, 'proyectos', 'Proyectos', 'Proyectos desarrollados en la asignatura.', 3),
  (7, 3, 'practicas', 'Prácticas', 'Prácticas y ejercicios aplicados.', 4),
  (8, 3, 'laboratorios-talleres', 'Laboratorios y talleres', 'Enunciados, soluciones y programas fuente.', 5),
  (9, 3, 'parciales', 'Parciales', 'Una subcarpeta por parcial con enunciado y respuestas corregidas.', 6),
  (21, 9, 'parcial-1', 'Parcial 1', 'Enunciado del parcial y hoja de respuestas con correcciones.', 1),
  (22, 9, 'parcial-2', 'Parcial 2', 'Enunciado del parcial y hoja de respuestas con correcciones.', 2),
  (23, 9, 'parcial-3', 'Parcial 3', 'Enunciado del parcial y hoja de respuestas con correcciones.', 3),
  (10, 3, 'otros', 'Otros', 'Foros, investigaciones, ensayos y resúmenes.', 7),
  (11, 3, 'actividades-adicionales', 'Actividades adicionales', 'Sección condicional: usar solo cuando corresponda.', 8),
  (12, 11, 'extracurriculares', 'Extracurriculares', 'Evidencias e informe, si esta actividad aplica.', 1),
  (13, 11, 'complementarias', 'Complementarias', 'Evidencias e informe, si esta actividad aplica.', 2),
  (14, 11, 'servicios-sociales', 'Servicios sociales', 'Evidencias e informe, si esta actividad aplica.', 3),
  (15, NULL, 'material-didactico', 'Material didáctico', 'Material del docente, referencias y recursos complementarios.', 4),
  (16, 15, 'material-docente', 'Material suministrado por el docente', 'Archivos de apoyo entregados en el curso.', 1),
  (17, 15, 'material-adicional', 'Material adicional', 'Recursos complementarios utilizados.', 2),
  (18, 15, 'bibliografia-consultada', 'Bibliografía consultada', 'Fuentes utilizadas para reforzar los temas.', 3),
  (19, 15, 'material-bibliografico-utp', 'Material bibliográfico UTP', 'Material de la Universidad Tecnológica de Panamá.', 4),
  (20, NULL, 'conclusion-final', 'Conclusión final', 'Reflexión de cierre del curso; se completa al terminar el semestre.', 5)
ON DUPLICATE KEY UPDATE
  parent_id = VALUES(parent_id), nombre = VALUES(nombre), descripcion = VALUES(descripcion), orden = VALUES(orden);

INSERT INTO entradas (id, seccion_id, titulo, descripcion, fecha_actividad, estado) VALUES
  (1, 1, 'Programa de Asignatura — Desarrollo Web', 'Programa oficial de la asignatura, código 1269.', NULL, 'publicado'),
  (2, 1, 'Planificación semestral del estudiante', 'Pendiente: agregar el PDF del formato FC-FISC-1-2-2017 cuando esté disponible.', NULL, 'borrador'),
  (3, 2, 'Presentación general del estudiante', 'Documento académico de una página con foto, formación, hobbies y expectativas.', NULL, 'publicado')
ON DUPLICATE KEY UPDATE
  seccion_id = VALUES(seccion_id), titulo = VALUES(titulo), descripcion = VALUES(descripcion), estado = VALUES(estado);

INSERT INTO archivos (id, entrada_id, tipo, nombre_original, ruta_guardada, mime_type, tamano) VALUES
  (1, 1, 'material', 'Programa de Asignatura — Desarrollo Web.pdf', 'portafolio/01-descripcion-del-curso/programa-asignatura.pdf', 'application/pdf', 257183),
  (2, 3, 'material', 'Presentación general del estudiante.pdf', 'portafolio/02-presentacion-general/presentacion-general.pdf', 'application/pdf', 98281)
ON DUPLICATE KEY UPDATE
  entrada_id = VALUES(entrada_id), tipo = VALUES(tipo), nombre_original = VALUES(nombre_original),
  ruta_guardada = VALUES(ruta_guardada), mime_type = VALUES(mime_type), tamano = VALUES(tamano);
