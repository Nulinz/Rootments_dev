<?php

return [
    'credentials' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase.json')),

    'project_id' => env('FIREBASE_PROJECT_ID', 'rootments-app'),

    'auth' => [
        'default_uid' => null,
    ],

    'messaging' => [
        'enabled' => true,
    ],
];
