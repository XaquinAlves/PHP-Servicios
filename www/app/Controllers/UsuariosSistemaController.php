<?php

declare(strict_types=1);

namespace Com\Daw2\Controllers;

use Ahc\Jwt\JWT;
use Com\Daw2\Core\BaseController;
use Com\Daw2\Libraries\Respuesta;
use Com\Daw2\Models\UsuariosSistemaModel;

class UsuariosSistemaController extends BaseController
{
    public function login(): void
    {
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            $model = new UsuariosSistemaModel();
            $usuario = $model->getByEmail($_POST['email']);
            if ($usuario === false) {
                $respuesta = new Respuesta(403);
            } elseif (password_verify($_POST['password'], $usuario['pass'])) {
                $respuesta = new Respuesta(200);
                //Generar token JWT
                $jwt = new JWT($_ENV['jwt.secret'], 'HS256', 1800, 10);
                $payload = ['id_usuario' => $usuario['id_usuario']];
                $token = $jwt->encode($payload);
                $respuesta->setData(['token' => $token]);
            } else {
                $respuesta = new Respuesta(403);
            }
        } else {
            $respuesta = new Respuesta(400);
        }
        $this->view->show('json.view.php', ['respuesta' => $respuesta]);
    }
}
