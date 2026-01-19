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
        $respuesta = new Respuesta(200);
        $listaCategorias = $model->getAllCategorias();
        foreach ($listaCategorias as &$categoria) {
            $padre = $categoria['padre'];
            while ($padre !== null) {
                $padre = $model->getById((int)$padre);
                $categoria['padre'] = $padre;
                $padre = $padre['padre'];
            }
        }
        $respuesta->setData($listaCategorias);
        $this->view->show('json.view.php', [ 'respuesta' => $respuesta ]);
    }

    public function getCategoria($id)
    {
        $model = new CategoriasModel();
        $categoria = $model->getById($id);
        if ($categoria) {
            $padre = $categoria['padre'];

            while ($padre !== null) {
                $padre = $model->getById((int)$padre);
                $categoria['padre'] = $padre;
                $padre = $padre['padre'];
            }

            $respuesta = new Respuesta(200);
            $respuesta->setData($categoria);
        } else {
            $respuesta = new Respuesta(404);
        }
        $this->view->show('json.view.php', [ 'respuesta' => $respuesta ]);
    }
}
