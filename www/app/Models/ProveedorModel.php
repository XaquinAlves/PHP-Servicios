<?php

declare(strict_types=1);

namespace Com\Daw2\Models;

use Com\Daw2\Core\BaseDbModel;

class ProveedorModel extends BaseDbModel
{
    public function getAllProveedores(): array
    {
        $sql = "SELECT prov.cif, prov.codigo, prov.nombre as nombre_proveedor, prov.direccion,
            prov.website as sitio_web, prov.pais, prov.email, prov.telefono, count(prod.codigo) as cantidad_productos
            FROM ud7.proveedor as prov LEFT JOIN ud7.producto prod on prov.cif = prod.proveedor 
            GROUP BY prov.cif LIMIT 0,25";
        return $this->pdo->query($sql)->fetchAll();
    }
}
