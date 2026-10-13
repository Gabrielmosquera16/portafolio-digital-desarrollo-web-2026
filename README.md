# Portafolio Digital Estudiantil — Desarrollo Web

Preliminar académico de **Gabriel Oriel Mosquera Magallón**, para la asignatura **Desarrollo Web** de la Universidad Tecnológica de Panamá (UTP). La aplicación usa **HTML5, CSS, PHP y MySQL/MariaDB**, y está organizada para ejecutarse localmente con XAMPP.

## Qué incluye

Portada web; perfil con foto, formación, rol profesional, hobbies y expectativas; descripción oficial del curso; y navegación por las secciones del portafolio. La portada indica la UTP, la Facultad de Ingeniería de Sistemas Computacionales, el Departamento de Programación de Computadoras, el código **1269**, la sección **1S3221**, el **II Semestre 2026** y el facilitador **Giovani Antonio Sánchez Gómez**. Se incluye su nombre, pero no datos de contacto.

El portafolio crea espacios para actividades y asignaciones, con carpetas para tareas, trabajos en clase, proyectos, prácticas, laboratorios/talleres, otros, actividades adicionales y **tres parciales** como indica la presentación del curso. Incluye también materiales docentes, complementarios, bibliografía consultada, recursos bibliográficos UTP y conclusión final. El preliminar incorpora el programa oficial y la presentación general del estudiante. **No inventa actividades ni una conclusión final.** El PDF de planificación semestral del estudiante (formato FC-FISC-1-2-2017) aún no estaba entre los archivos recibidos; está marcado como pendiente.

La aplicación cuenta con un panel administrativo para crear, editar, publicar y eliminar entradas. Admite enunciados, soluciones y materiales PDF; aplica consultas PDO preparadas, escape HTML, protección CSRF, contraseña con `password_hash` y almacenamiento de documentos fuera del directorio servido.

## Requisitos

- XAMPP con Apache y MySQL activos.
- PHP 8.1 o posterior, con `pdo_mysql`, `fileinfo` y `mbstring` habilitados (normalmente vienen incluidos en XAMPP).
- Navegador web y phpMyAdmin, incluido en XAMPP.

## Instalación local en XAMPP

1. Descarga o clona el repositorio y copia la carpeta dentro de `C:\xampp\htdocs\`. Conserva el nombre `portafolio-digital-desarrollo-web-2026` o ajusta la dirección del paso 5.
2. Abre el Panel de Control de XAMPP e inicia **Apache** y **MySQL**.
3. Entra a `http://localhost/phpmyadmin/`. Importa primero `database/schema.sql` y luego `database/seed.sql`. Se crea la base `portafolio_digital` y se cargan el perfil y las secciones iniciales.
4. En la carpeta del proyecto, copia `config/database.example.php` como `config/database.php`. Desde una consola abierta en la raíz del proyecto:

   ```bat
   copy config\database.example.php config\database.php
   ```

   Edita `config/database.php` con el usuario y la contraseña de MySQL de **tu instalación**. `root` sin contraseña es solo el ejemplo habitual de XAMPP; si configuraste otra, usa tus valores locales. **No publiques `database.php` ni credenciales en GitHub.**
5. Abre `http://localhost/portafolio-digital-desarrollo-web-2026/`. La raíz redirige a la aplicación PHP.
6. Selecciona **Administrar** y crea tu usuario y una contraseña propia de al menos 12 caracteres. El alta inicial solo funciona desde `localhost` y se cierra después del primer registro.

## Agregar actividades y documentos

Elige la sección y completa título y descripción. Para las actividades, conserva el enunciado original y su solución cuando corresponda; para cada parcial, utiliza su subcarpeta y adjunta el enunciado y las respuestas corregidas. Solo se aceptan archivos PDF válidos de hasta 10 MB. Se guardan con nombre aleatorio y fuera de la carpeta pública; la descarga pasa por un controlador que valida la ruta. Deja como **Borrador** cualquier evidencia incompleta; solo lo que marques **Publicado** será visible a otras personas.

## Estructura principal

```text
app/                          Clases de conexión, consultas, seguridad y archivos
config/                       Ejemplo de conexión local, sin credenciales
database/                     Esquema MySQL y datos semilla
public/                       Vistas PHP, CSS, foto y panel administrativo
storage/portafolio/            PDFs y árbol de carpetas académicas privado
docs/                          Arquitectura y guía de estructura académica
```

La carpeta del repositorio que contiene el PDF de presentación es `storage/portafolio/02-presentacion-general/`; el PDF se sirve por la ruta protegida de la aplicación. El detalle de carpetas está en [`docs/estructura-portafolio.md`](docs/estructura-portafolio.md), y el recorrido técnico en [`docs/arquitectura.md`](docs/arquitectura.md).

## Repositorio y ejecución

Repositorio público: <https://github.com/Gabrielmosquera16/portafolio-digital-desarrollo-web-2026>.

GitHub permite revisar el código y los documentos versionados, pero **no ejecuta PHP ni proporciona MySQL**. La versión funcional del preliminar corre localmente en XAMPP en `http://localhost/…`. Para que el profesor pueda usarla en Internet se necesita un hosting compatible con PHP y MySQL; el repositorio por sí solo no publica esta aplicación.

## Privacidad

La versión pública omite la cédula que aparecía en el material de portada original y excluye la presentación del curso que expone datos personales de contacto del facilitador. Sí muestra su nombre profesional, necesario para identificar el curso. No se incluyen contraseñas ni configuración de base de datos real. Las evidencias no proporcionadas permanecen como espacios pendientes.
