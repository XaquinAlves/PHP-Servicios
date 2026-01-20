<?php

namespace Com\Daw2\Core;

use Com\Daw2\Controllers\CategoriaController;
use Steampixel\Route;

class FrontController
{
    public static function main()
    {
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
            $controller = new CategoriaController();
            $controller->getAllCategorias();
        }, 'get');

        Route::add('/categoria/(\d{1,3})', function ($id) {
            $controller = new CategoriaController();
            $controller->getCategoria((int)$id);
        }, 'get');

        Route::add('/categoria', function () {
            $controller = new CategoriaController();
            $controller->postCategoria();
        }, 'post');

        Route::run();
    }
}
