<?php
$router->define([
    'GET' => [
        '/admin' => [
            'controller' => 'AdminLoginController',
            'method' => 'index'
        ],
        '/' => [
            'controller' => 'HomeController',
            'method' => 'index'
        ],
        '/logout' => [
            'controller' => 'AdminLogoutController',
            'method' => 'index'
        ],
        '/users' => [
            'controller' => 'AdminUsersController',
            'method' => 'index'
        ],
        '/products' => [
            'controller' => 'AdminProductController',
            'method' => 'index'
        ]
    ],
    'POST' => [
        '/admin' => [
            'controller' => 'AdminLoginController',
            'method' => 'login'
        ],
        '/register' => [
            'controller' => 'RegisterController',
            'method' => 'register'
        ],
    ],
    'DELETE' => [
        '/users' => [
            'controller' => 'AdminUserController',
            'method' => 'delete'
        ],
        '/products' => [
            'controller' => 'AdminProductController',
            'method' => 'delete'
        ]
    ]
]);