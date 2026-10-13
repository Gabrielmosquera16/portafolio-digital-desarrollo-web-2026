# Arquitectura del preliminar

## Recorrido de una página

1. Apache entrega un archivo dentro de `public/`.
2. La vista incluye `app/bootstrap.php`, que inicia la sesión, carga `config/database.php` y crea una conexión PDO.
3. `PortfolioRepository` consulta el perfil, las secciones, las entradas y los adjuntos mediante sentencias preparadas.
4. La vista combina los resultados con plantillas PHP y los presenta en HTML5. `e()` escapa los valores antes de insertarlos en el HTML.
5. El CSS externo de `public/assets/css/estilos.css` controla la presentación responsive.

## Componentes

- `Database`: construye PDO con `utf8mb4`, excepciones y consultas nativas preparadas.
- `PortfolioRepository`: centraliza las operaciones de lectura y escritura en MySQL.
- `Security`: maneja escape HTML, rutas locales, sesión administrativa, mensajes temporales y tokens CSRF.
- `FileStorage`: limita los archivos a PDF, revisa MIME y tamaño, crea nombres aleatorios y verifica que la descarga permanezca dentro de `storage/`.
- `public/admin/`: configura el primer administrador únicamente desde localhost y después ofrece inicio de sesión y CRUD.
- `database/schema.sql` / `database/seed.sql`: definen las tablas y cargan información inicial sin contraseñas.

## Modelo de datos

- `perfil_estudiante`: perfil y expectativas del estudiante.
- `intereses`: pasatiempos.
- `secciones`: árbol de carpetas; `parent_id` enlaza cada subsección a su carpeta padre.
- `entradas`: trabajos o documentos, ligados a una sección y con estado `borrador` o `publicado`.
- `archivos`: PDF asociados a una entrada y clasificados como `enunciado`, `solucion` o `material`.
- `administradores`: usuario y hash de contraseña generado con `password_hash`.

## Medidas implementadas

- PDO y parámetros enlazados para limitar inyección SQL.
- Escape HTML con `htmlspecialchars` para mitigar XSS almacenado.
- Token aleatorio CSRF en formularios POST del panel.
- `password_hash` / `password_verify`, regeneración del identificador de sesión y cookie `HttpOnly`.
- Alta del administrador solo desde `127.0.0.1` o `::1`; sin usuario ni contraseña de fábrica.
- Validación de PDF por extensión, MIME real y límite de 10 MB.
- Archivos fuera de `public/`, descarga controlada y bloqueo HTTP de `app/`, `config/`, `database/` y `storage/` mediante Apache.
- Contenido de borrador oculto a visitantes.

## Límites del preliminar

La aplicación está preparada para XAMPP local. La URL de GitHub permite examinar los archivos, pero no ejecuta PHP ni ofrece una base de datos. El material que aún no fue proporcionado se debe incorporar cuando el estudiante lo reciba; no se deben publicar borradores incompletos como si fueran actividades terminadas.
