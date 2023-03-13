<?php

return [
    "messages" => [
      "ru" => [
          400 => "неправильный, некорректный запрос",
          401 => "не авторизован (не представился)",
          403 => "запрещено (не уполномочен)",
          404 => "не найдено",
          405 => "метод не поддерживается",
          500 => "внутренняя ошибка сервера",
      ],
      "uz" => [
          400 => "Xato so'rov",
          401 => "Tizimda ro'yxatdan o'tmagansiz",
          403 => "Ruxsat yo'q",
          404 => "Sahifa topilmadi",
          405 => "So'rov metodi xato",
          500 => "Server xatosi",
      ],
      "en" => [
          400 => "Bad request",
          401 => "Unauthorized",
          403 => "Forbidden",
          404 => "Not found",
          405 => "Method not allowed",
          500 => "Internal server error",
      ],
    ],
    "permissions" => [
        [
            'name' => 'permission.index'
        ],
        [
            'name' => 'permission.show'
        ],
        [
            'name' => 'permission.store'
        ],
        [
            'name' => 'permission.edit'
        ],
        [
            'name' => 'permission.update'
        ],
        [
            'name' => 'permission.delete'
        ],
        [
            'name' => 'user.index'
        ],
        [
            'name' => 'user.show'
        ],
        [
            'name' => 'user.store'
        ],
        [
            'name' => 'user.edit'
        ],
        [
            'name' => 'user.update'
        ],
        [
            'name' => 'user.delete'
        ],
        [
            'name' => 'role.index'
        ],
        [
            'name' => 'role.show'
        ],
        [
            'name' => 'role.store'
        ],
        [
            'name' => 'role.edit'
        ],
        [
            'name' => 'role.update'
        ],
        [
            'name' => 'role.delete'
        ],
    ],
    'roles' => [
        [
            'name' => 'superAdmin'
        ],
        [
            'name' => 'admin'
        ],
        [
            'name' => 'simpleUser'
        ],
    ],
];
