<?php

return [
    'role_structure' => [
        'superadmin' => [
            'autoparts' => 'r,c,u,d',
            'contacts' => 'r,c,u,d',
            'events' => 'r,c,u,d',
            'files' => 'r,c,u,d',
            'news' => 'r,c,u,d',
            'profile' => 'r,u',
            'roles' => 'r,c,u,d',
            'tasks' => 'r,c,u,d',
            'users' => 'r,c,u,d',
        ],
        'admin' => [
            'autoparts' => 'r,c,u,d',
            'contacts' => 'r,c,u,d',
            'events' => 'r,c,u,d',
            'files' => 'r,c,u,d',
            'news' => 'r,c,u,d',
            'profile' => 'r,u',
            'tasks' => 'r,c,u,d',
            'users' => 'r,c,u,d',
        ],
        'user' => [
            'autoparts' => 'r,c,u,d',
            'contacts' => 'r,c,u,d',
            'events' => 'r,c,u,d',
            'files' => 'r,c,u,d',
            'news' => 'r,c,u,d',
            'profile' => 'r,u',
            'tasks' => 'r,c,u,d',
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
