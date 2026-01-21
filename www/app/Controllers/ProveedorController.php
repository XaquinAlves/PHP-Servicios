<?php

declare(strict_types=1);

namespace Com\Daw2\Controllers;

use Com\Daw2\Core\BaseController;
use Com\Daw2\Libraries\Respuesta;
use Com\Daw2\Models\ProveedorModel;

class ProveedorController extends BaseController
{
    public function getAllProveedores(): void
    {
        $model = new ProveedorModel();
        $proveedores = $model->getAllProveedores();
        $respuesta = new Respuesta(200);
        $respuesta->setData($proveedores);
        $this->view->show('json.view.php', [ 'respuesta' => $respuesta ]);
    }
}