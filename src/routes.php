<?php
return [
    '~^articles/add$~' => [\MyProject\Controller\ArticlesController::class, 'add'],
    '~^articles/(\d+)$~' => [\MyProject\Controller\ArticlesController::class, 'view'],
    '~^articles/(\d+)/edit$~' => [\MyProject\Controller\ArticlesController::class, 'edit'],
    '~^articles/(\d+)/delete~' => [\MyProject\Controller\ArticlesController::class, 'delete'],
    '~^users/login~' => [\MyProject\Controller\UsersController::class, 'login'],
    '~^users/logout~' => [\MyProject\Controller\UsersController::class, 'logout'],
    '~^users/register~' => [\MyProject\Controller\UsersController::class, 'register'],
    '~^users/(\d+)/activate/(.+)~' => [\MyProject\Controller\UsersController::class, 'activate'],
    '~^$~' => [\MyProject\Controller\ArticlesController::class, 'main']
];