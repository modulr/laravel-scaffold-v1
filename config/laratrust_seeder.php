<?php

return [
    'role_structure' => [
        'admin' => [
            'users' => 'r,c,u,d',
            'roles' => 'r,c,u,d'
        ]
    ],
    // 'permission_structure' => [
    //     'cru_user' => [
    //         'profile' => 'r,u'
    //     ],
    // ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
