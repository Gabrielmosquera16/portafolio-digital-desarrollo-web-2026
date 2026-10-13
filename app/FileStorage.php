<?php
declare(strict_types=1);

final class FileStorage
{
    private const MAX_BYTES = 10_485_760;

    private const SECTION_FOLDERS = [
        'descripcion-del-curso' => 'portafolio/01-descripcion-del-curso',
        'presentacion-general' => 'portafolio/02-presentacion-general',
        'actividades-asignaciones' => 'portafolio/03-actividades',
        'tareas-asignadas' => 'portafolio/03-actividades/tareas',
        'trabajos-en-clase' => 'portafolio/03-actividades/trabajos-en-clase',
        'proyectos' => 'portafolio/03-actividades/proyectos',
        'practicas' => 'portafolio/03-actividades/practicas',
        'laboratorios-talleres' => 'portafolio/03-actividades/laboratorios-talleres',
        'parciales' => 'portafolio/03-actividades/parciales',
        'parcial-1' => 'portafolio/03-actividades/parciales/parcial-1',
        'parcial-2' => 'portafolio/03-actividades/parciales/parcial-2',
        'parcial-3' => 'portafolio/03-actividades/parciales/parcial-3',
        'parcial-4' => 'portafolio/03-actividades/parciales/parcial-4',
        'otros' => 'portafolio/03-actividades/otros',
        'actividades-adicionales' => 'portafolio/03-actividades/actividades-adicionales',
        'extracurriculares' => 'portafolio/03-actividades/actividades-adicionales/extracurriculares',
        'complementarias' => 'portafolio/03-actividades/actividades-adicionales/complementarias',
        'servicios-sociales' => 'portafolio/03-actividades/actividades-adicionales/servicios-sociales',
        'material-didactico' => 'portafolio/04-material-didactico',
        'material-docente' => 'portafolio/04-material-didactico/material-docente',
        'material-adicional' => 'portafolio/04-material-didactico/material-adicional',
        'bibliografia-consultada' => 'portafolio/04-material-didactico/bibliografia',
        'material-bibliografico-utp' => 'portafolio/04-material-didactico/material-utp',
        'conclusion-final' => 'portafolio/05-conclusion-final',
    ];

    public function __construct(private readonly string $storageRoot)
    {
    }

    /** @return array{original_name:string, stored_path:string, mime_type:string, file_size:int} */
    public function savePdf(array $upload, string $sectionSlug): array
    {
        if (($upload['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            throw new InvalidArgumentException('No se seleccionó ningún archivo.');
        }
        if (($upload['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || !is_uploaded_file($upload['tmp_name'] ?? '')) {
            throw new InvalidArgumentException('No se pudo recibir el archivo. Intenta nuevamente.');
        }

        $size = (int) ($upload['size'] ?? 0);
        if ($size < 1 || $size > self::MAX_BYTES) {
            throw new InvalidArgumentException('El PDF debe pesar entre 1 byte y 10 MB.');
        }

        $extension = strtolower(pathinfo((string) ($upload['name'] ?? ''), PATHINFO_EXTENSION));
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($upload['tmp_name']);
        if ($extension !== 'pdf' || $mime !== 'application/pdf') {
            throw new InvalidArgumentException('Solo se permiten archivos PDF válidos.');
        }

        $folder = self::SECTION_FOLDERS[$sectionSlug] ?? null;
        if ($folder === null) {
            throw new InvalidArgumentException('La sección elegida no tiene una carpeta de almacenamiento configurada.');
        }
        $relativeFolder = $folder . '/uploads';
        $directory = $this->storageRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativeFolder);
        if (!is_dir($directory) && !mkdir($directory, 0750, true) && !is_dir($directory)) {
            throw new RuntimeException('No se pudo preparar la carpeta privada de archivos.');
        }

        $storedName = bin2hex(random_bytes(20)) . '.pdf';
        if (!move_uploaded_file($upload['tmp_name'], $directory . DIRECTORY_SEPARATOR . $storedName)) {
            throw new RuntimeException('No se pudo guardar el PDF.');
        }

        $originalName = basename((string) $upload['name']);
        $originalName = preg_replace('/[^\pL\pN._ -]/u', '_', $originalName) ?: 'documento.pdf';

        return [
            'original_name' => mb_substr($originalName, 0, 180, 'UTF-8'),
            'stored_path' => $relativeFolder . '/' . $storedName,
            'mime_type' => 'application/pdf',
            'file_size' => $size,
        ];
    }

    public function resolve(string $relativePath): string
    {
        if ($relativePath === '' || str_contains($relativePath, "\0")) {
            throw new RuntimeException('La ruta del archivo no es válida.');
        }

        $root = realpath($this->storageRoot);
        $target = realpath($this->storageRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath));
        if ($root === false || $target === false || !is_file($target)) {
            throw new RuntimeException('El documento no está disponible.');
        }

        $prefix = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if (!str_starts_with($target, $prefix)) {
            throw new RuntimeException('La ruta solicitada no está permitida.');
        }

        return $target;
    }

    public function delete(string $relativePath): void
    {
        if (preg_match('#(?:^|/)uploads/[a-f0-9]{40}\.pdf$#i', $relativePath) !== 1) {
            return;
        }
        try {
            @unlink($this->resolve($relativePath));
        } catch (RuntimeException) {
            // Un archivo ausente ya no requiere ninguna acción.
        }
    }
}
