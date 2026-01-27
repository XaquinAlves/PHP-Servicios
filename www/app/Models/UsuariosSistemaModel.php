<?php

declare(strict_types=1);

namespace Com\Daw2\Models;

use Com\Daw2\Core\BaseDbModel;

class UsuariosSistemaModel extends BaseDbModel
{
    private const ADMINISTRADOR = 1;
    private const AUDITOR = 2;
    private const FACTURACION = 3;
    public function getByEmail(string $email): array|false
    {
        $sql = "SELECT * FROM ud7.usuario_sistema WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $sql = "SELECT * FROM ud7.usuario_sistema WHERE id_usuario = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $usuario = $stmt->fetch();
        if ($usuario !== false) {
            $usuario['permisos'] = $this->getPermisos((int)$usuario['id_rol']);
        }
        return $usuario;
    }

    private function getPermisos(int $idRol): array
    {
        $permisos = [
            'categorias' => ''
        ];
        if ($idRol == self::ADMINISTRADOR) {
            $permisos['categorias'] = 'rwd';
        } elseif ($idRol == self::AUDITOR) {
            $permisos['categorias'] = 'r';
        } elseif ($idRol == self::FACTURACION) {
            $permisos['categorias'] = 'rw';
        }
        return $permisos;
    }
}
