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

    public function getById(int $id)
    {
        $sql = "SELECT cat.id_categoria, cat.nombre_categoria, cat.id_padre as padre FROM categoria as cat 
            WHERE id_categoria = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
