<?php

declare(strict_types=1);

namespace Com\Daw2\Controllers;

use Com\Daw2\Core\BaseController;
use Com\Daw2\Libraries\Respuesta;
use Com\Daw2\Models\CategoriasModel;

class CategoriaController extends BaseController
{
    public function getAllCategorias(): void
    {
        $model = new CategoriasModel();
        $respuesta = new Respuesta(200);
        $listaCategorias = $model->getAllCategorias();
        foreach ($listaCategorias as &$categoria) {
            $categoria = $this->getFullCategoria((int)$categoria['id_categoria']);
        }
        $respuesta->setData($listaCategorias);
        $this->view->show('json.view.php', [ 'respuesta' => $respuesta ]);
    }

    public function getCategoria($id): void
    {
        $categoria = $this->getFullCategoria($id);
        if ($categoria) {
            $respuesta = new Respuesta(200);
            $respuesta->setData($categoria);
        } else {
            $respuesta = new Respuesta(404);
        }
        $this->view->show('json.view.php', [ 'respuesta' => $respuesta ]);
    }

    private function getFullCategoria($id): array|false
    {
        $model = new CategoriasModel();
        $categoria = $model->getById($id);
        if ($categoria === false) {
            return false;
        }
        if (is_null($categoria['padre'])) {
            return $categoria;
        } else {
            $categoria['padre'] = $this->getFullCategoria((int)$categoria['padre']);
            return $categoria;
        }
    }

    public function postCategoria(): void
    {
        $model = new CategoriasModel();

        $errors = $this->checkErrors($_POST);

        if (isset($errors['duplicado'])) {
            $respuesta = new Respuesta(409);
            $respuesta->setData(['Duplicado' => 'La categoría ya existe']);
        } elseif ($errors !== []) {
            $respuesta = new Respuesta(400);
            $respuesta->setData($errors);
        } else {
            $result = $model->insertCategoria($_POST);
            if ($result) {
                $respuesta = new Respuesta(201);
                $respuesta->setData($model->searchByNameAndFather(
                    $_POST['categoria'],
                    isset($_POST['id_padre']) ? (int)$_POST['id_padre'] : null
                ));
            } else {
                $respuesta = new Respuesta(500);
            }
        }

        $this->view->show('json.view.php', [ 'respuesta' => $respuesta ]);
    }

    public function deleteCategoria($id): void
    {
        $model = new CategoriasModel();
        try {
            $result = $model->deleteCategoria($id);
            $respuesta = new Respuesta($result === false ? 404 : 200);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                $respuesta = new Respuesta(409);
                $respuesta->setData(
                    ['padre' => 'Esta categoria es padre de otras categorías, es necesario eliminarlas primero ']
                );
            } else {
                throw $e;
            }
        }

            $this->view->show('json.view.php', ['respuesta' => $respuesta]);
    }

    public function putCategoria(int $id): void
    {
        $model = new CategoriasModel();
        if ($model->getById($id)) {
            $params = array_merge($this->initBodyData(), ['id' => $id]);
            $errors = $this->checkErrors($params, true);
            if ($errors !== []) {
                if (isset($errors['duplicado'])) {
                    $respuesta = new Respuesta(409);
                    $respuesta->setData(["Duplicado" => "Existe otra categoria con esos datos"]);
                } else {
                    $respuesta = new Respuesta(400);
                    $respuesta->setData(array_merge($errors, $params));
                }
            } else {
                if ($model->uptadeFullCategoria($params)) {
                    $respuesta = new Respuesta(200);
                    $respuesta->setData($model->getById($id));
                } else {
                    $respuesta = new Respuesta(500);
                }
            }
        } else {
            $respuesta = new Respuesta(404);
        }
        $this->view->show('json.view.php', [ 'respuesta' => $respuesta ]);
    }

    private function initBodyData(): array
    {
        $request = file_get_contents('php://input');
        $contentType = $_SERVER['CONTENT_TYPE'] ?? 'plain/text';
        if (!empty($request)) {
            if ($contentType === 'application/json') {
                $postVars = json_decode($request, true);
            } else {
                parse_str($request, $postVars);
            }
            return $postVars;
        } else {
            return [];
        }
    }

    private function checkErrors(array $data, ?bool $putMode = false): array
    {
        $errors = [];
        $model = new CategoriasModel();
        if (empty($data['categoria'])) {
            $errors['categoria'] = "Campo obligatorio";
        }
        if (isset($data['id_padre'])) {
            if (!filter_var($data['id_padre'], FILTER_VALIDATE_INT) && $data['id_padre'] !== null) {
                $errors['id_padre'] = "El padre debe ser un entero o null";
            } else {
                if ($data['id_padre'] !== null) {
                    if (!$model->getById((int)$data['id_padre'])) {
                        $errors['id_padre'] = 'El padre no existe';
                    }
                }
            }
        }

        if ($errors === []) {
            $duplicado = $model->searchByNameAndFather($data['categoria'], isset($data['id_padre']) ?
                (int)$data['id_padre'] : null);
            if ($duplicado !== false) {
                if (!$putMode || (int)$duplicado['id_categoria'] !== (int)$data['id']) {
                    $errors['duplicado'] = true;
                }
            }
        }
        return $errors;
    }
}
