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
            'name' => 'permission.index',
            'description' => "Barcha ruxsatlarni ko'rish"
        ],
        [
            'name' => 'permission.store',
            'description' => "Ruxsatni Yaratish"
        ],
        [
            'name' => 'permission.edit',
            'description' => "Ruxsatlarni tahrirlash"
        ],
        [
            'name' => 'permission.update',
            'description' => "Ruxsatlarni yangilash"
        ],
        [
            'name' => 'permission.delete',
            'description' => "Ruxsatlarni o'chirish"
        ],
        //////////////////////////////////////
        [
            'name' => 'user.index',
            'description' => "Foydalanuvchilarni ko'rish"
        ],
        [
            'name' => 'user.store',
            'description' => "Foydalanuvchini saqlash"
        ],
        [
            'name' => 'user.edit',
            'description' => "Foydalanuvchini tahrirlash"
        ],
        [
            'name' => 'user.update',
            'description' => "Foydalanuvchini yangilash"
        ],
        [
            'name' => 'user.delete',
            'description' => "Foydalanuvchini o'chirish"
        ],
        [
            'name' => 'user.getPassword',
            'description' => "Foydalanuvchi parolini ko'rish"
        ],
        ///////////////////////////////
        [
            'name' => 'role.index',
            'description' => "rollarni ko'rish"
        ],
        [
            'name' => 'role.store',
            'description' => "Rolni yaratish"
        ],
        [
            'name' => 'role.edit',
            'description' => "Rolni tahrirlash"
        ],
        [
            'name' => 'role.update',
            'description' => "Rolni yangilash"
        ],
        [
            'name' => 'role.delete',
            'description' => "Rolni o'chirish"
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
            'name' => 'student'
        ],
        [
            'name' => 'simpleUser'
        ],
    ],
];
