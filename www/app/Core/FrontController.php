<?php

namespace Com\Daw2\Core;

use Ahc\Jwt\JWT;
use Com\Daw2\Controllers\CategoriaController;
use Com\Daw2\Controllers\ProveedorController;
use Com\Daw2\Controllers\UsuariosSistemaController;
use Com\Daw2\Libraries\JWTHelper;
use Com\Daw2\Libraries\JwtTool;
use Com\Daw2\Models\UsuariosSistemaModel;
use Steampixel\Route;

class FrontController
{
    private static false|array $user = false;
    public static function main()
    {
        if (JwtTool::requestHasToken()) {
            $token = JwtTool::getBearerToken();
            $payload = (new JWTHelper())->decodeToken($token);
            self::$user = (new UsuariosSistemaModel())->findById($payload['id_usuario']);
        }
        Route::add('/login', function () {
            $controller = new UsuariosSistemaController();
            $controller->login();
        }, 'post');

        Route::pathNotFound(
            function () {
                http_response_code(404);
            }
        );

        Route::methodNotAllowed(
            function () {
                http_response_code(405);
            }
        );

        Route::add('/categoria', function () {
            if (self::$user !== false && str_contains(self::$user['permisos']['categorias'], 'r')) {
                $controller = new CategoriaController();
                $controller->getAllCategorias();
            } else {
                http_response_code(403);
            }
        }, 'get');

        Route::add('/categoria/(\d{1,3})', function ($id) {
            if (self::$user !== false && str_contains(self::$user['permisos']['categorias'], 'r')) {
                $controller = new CategoriaController();
                $controller->getCategoria((int)$id);
            } else {
                http_response_code(403);
            }
        }, 'get');

        Route::add('/categoria', function () {
            if (self::$user !== false && str_contains(self::$user['permisos']['categorias'], 'w')) {
                $controller = new CategoriaController();
                $controller->postCategoria();
            } else {
                http_response_code(403);
            }
        }, 'post');

        Route::add('/categoria/(\d{1,3})', function ($id) {
            if (self::$user !== false && str_contains(self::$user['permisos']['categorias'], 'd')) {
                $controller = new CategoriaController();
                $controller->deleteCategoria((int)$id);
            } else {
                http_response_code(403);
            }
        }, 'delete');

        Route::add('/categoria/(\d{1,3})', function ($id) {
            if (self::$user !== false && str_contains(self::$user['permisos']['categorias'], 'w')) {
                $controller = new CategoriaController();
                $controller->putCategoria((int)$id);
            }
        }, 'put');

        Route::add('/proveedor', function () {
            $controller = new ProveedorController();
            $controller->getAllProveedores();
        }, 'get');

        Route::run();
    }
}
