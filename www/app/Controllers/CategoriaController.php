<?php

declare(strict_types=1);

namespace Com\Daw2\Controllers;

use Com\Daw2\Core\BaseController;
use Com\Daw2\Libraries\Respuesta;
use Com\Daw2\Models\CategoriasModel;

class CategoriaController extends BaseController
{
    public function getAllCategorias()
    {
        $model = new CategoriasModel();
        $respuesta = new Respuesta(200, $model->getAllCategorias());
        $this->view->show('json.view.php', [ 'respuesta' => $respuesta ]);
    }
}
