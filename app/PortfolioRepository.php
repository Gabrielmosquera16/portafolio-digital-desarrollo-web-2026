<?php
declare(strict_types=1);

final class PortfolioRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }

    public function profile(): array
    {
        $row = $this->pdo->query('SELECT * FROM perfil_estudiante WHERE id = 1')->fetch();
        return $row ?: [];
    }

    public function interests(): array
    {
        return $this->pdo->query('SELECT nombre, descripcion FROM intereses ORDER BY orden, id')->fetchAll();
    }

    public function rootSections(): array
    {
        $sql = 'WITH RECURSIVE subtree AS (
                    SELECT id AS root_id, id AS section_id FROM secciones WHERE parent_id IS NULL
                    UNION ALL
                    SELECT tree.root_id, child.id FROM secciones child
                    INNER JOIN subtree tree ON child.parent_id = tree.section_id
                )
                SELECT s.id, s.slug, s.nombre, s.descripcion,
                       (SELECT COUNT(*) FROM entradas e
                        INNER JOIN subtree tree ON tree.section_id = e.seccion_id
                        WHERE tree.root_id = s.id AND e.estado = \'publicado\') AS cantidad
                FROM secciones s WHERE s.parent_id IS NULL ORDER BY s.orden, s.id';
        return $this->pdo->query($sql)->fetchAll();
    }

    public function allSections(): array
    {
        return $this->pdo->query('SELECT id, slug, nombre, parent_id FROM secciones ORDER BY COALESCE(parent_id, id), parent_id IS NOT NULL, orden, id')->fetchAll();
    }

    public function sectionBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, slug, nombre, descripcion, parent_id FROM secciones WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function publishedEntries(int $sectionId): array
    {
        $stmt = $this->pdo->prepare('SELECT id, seccion_id, titulo, descripcion, fecha_actividad, estado, creado_en
                                     FROM entradas WHERE seccion_id = :section AND estado = \'publicado\'
                                     ORDER BY fecha_actividad DESC, id DESC');
        $stmt->execute(['section' => $sectionId]);
        return $stmt->fetchAll();
    }

    public function allEntries(): array
    {
        $sql = 'SELECT e.id, e.titulo, e.estado, e.creado_en, s.nombre AS seccion
                FROM entradas e JOIN secciones s ON s.id = e.seccion_id
                ORDER BY e.creado_en DESC, e.id DESC';
        return $this->pdo->query($sql)->fetchAll();
    }

    public function entryById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT e.*, s.nombre AS seccion, s.slug AS seccion_slug
                                     FROM entradas e JOIN secciones s ON s.id = e.seccion_id WHERE e.id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function filesForEntry(int $entryId): array
    {
        $stmt = $this->pdo->prepare('SELECT id, entrada_id, tipo, nombre_original, ruta_guardada, mime_type, tamano
                                     FROM archivos WHERE entrada_id = :id ORDER BY id');
        $stmt->execute(['id' => $entryId]);
        return $stmt->fetchAll();
    }

    public function fileById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT a.*, e.estado AS estado_entrada
                                     FROM archivos a JOIN entradas e ON e.id = a.entrada_id WHERE a.id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function adminByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, usuario, password_hash FROM administradores WHERE usuario = :usuario LIMIT 1');
        $stmt->execute(['usuario' => $username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function adminCount(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM administradores')->fetchColumn();
    }

    public function createAdmin(string $username, string $passwordHash): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO administradores (usuario, password_hash) VALUES (:usuario, :hash)');
        $stmt->execute(['usuario' => $username, 'hash' => $passwordHash]);
    }

    public function createEntry(array $data): int
    {
        $sql = 'INSERT INTO entradas (seccion_id, titulo, descripcion, fecha_actividad, estado)
                VALUES (:seccion_id, :titulo, :descripcion, :fecha_actividad, :estado)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return (int) $this->pdo->lastInsertId();
    }

    public function updateEntry(int $id, array $data): void
    {
        $data['id'] = $id;
        $sql = 'UPDATE entradas SET seccion_id = :seccion_id, titulo = :titulo, descripcion = :descripcion,
                fecha_actividad = :fecha_actividad, estado = :estado WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    public function addFile(int $entryId, string $kind, array $file): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO archivos (entrada_id, tipo, nombre_original, ruta_guardada, mime_type, tamano)
                                     VALUES (:entrada_id, :tipo, :nombre, :ruta, :mime, :tamano)');
        $stmt->execute([
            'entrada_id' => $entryId,
            'tipo' => $kind,
            'nombre' => $file['original_name'],
            'ruta' => $file['stored_path'],
            'mime' => $file['mime_type'],
            'tamano' => $file['file_size'],
        ]);
    }

    public function deleteEntry(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM entradas WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
