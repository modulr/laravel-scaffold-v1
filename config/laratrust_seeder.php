<?php

return [
    'role_structure' => [
        'admin' => [
            'profile' => 'r,u',
            'news' => 'r,c,u,d',
            'tasks' => 'r,c,u,d',
            'files' => 'r,c,u,d',
            'events' => 'r,c,u,d',
            'contacts' => 'r,c,u,d',
            'autoparts' => 'r,c,u,d',
            'users' => 'r,c,u,d',
            'roles' => 'r,c,u,d',
        ],
        'user' => [
            'profile' => 'r,u',
            'news' => 'r,c,u,d',
            'tasks' => 'r,c,u,d',
            'files' => 'r,c,u,d',
            'events' => 'r,c,u,d',
            'contacts' => 'r,c,u,d',
            'autoparts' => 'r,c,u,d',
        ],
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
