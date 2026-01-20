<?php

declare(strict_types=1);

namespace Com\Daw2\Models;

use Com\Daw2\Core\BaseDbModel;

class CategoriasModel extends BaseDbModel
{
    public function getAllCategorias(): array
    {
        $sql = "SELECT cat.id_categoria, cat.nombre_categoria, cat.id_padre as padre FROM categoria as cat ";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $sql = "SELECT cat.id_categoria, cat.nombre_categoria, cat.id_padre as padre FROM categoria as cat 
            WHERE id_categoria = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function insertCategoria(array $datos): bool
    {
        $sql = "INSERT INTO categoria (nombre_categoria, id_padre) VALUES (:nombre, :padre)";
        $params = [
            'nombre' => $datos['categoria'],
            'padre' => $datos['id_padre'] ?? null
        ];
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function searchByNameAndFather(string $nombre, ?int $padre = null): array|false
    {
        if ($padre !== null) {
            $sql = "SELECT * FROM categoria WHERE nombre_categoria = :nombre AND id_padre = :padre";
            $params = [
                'nombre' => $nombre,
                'padre' => $padre
            ];
        } else {
            $sql = "SELECT * FROM categoria WHERE nombre_categoria = :nombre AND id_padre IS NULL";
            $params = [
                'nombre' => $nombre
            ];
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }
}
