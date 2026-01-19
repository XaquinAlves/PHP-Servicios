<?php

declare(strict_types=1);

namespace Com\Daw2\Models;

use Com\Daw2\Core\BaseDbModel;

class CategoriasModel extends BaseDbModel
{
    public function getAllCategorias(): array
    {
        $sql = "SELECT id_categoria, nombre_categoria, id_padre FROM categoria";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getById(int $id): array
    {
        $sql = "SELECT id_categoria, nombre_categoria, id_padre FROM categoria WHERE id_categoria = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
