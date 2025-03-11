<?php

return [
    'table_name' => env('OPERATION_LOG_TABLE_NAME', 'operation_logs'),
    'connection' => env('OPERATION_LOG_DB_CONNECTION', config('database.default', 'sqlite')),
    'user_model' => env('OPERATION_LOG_USER_MODEL', 'App\Models\User'),
    'guard' => env('OPERATION_LOG_GUARD', config('auth.defaults.guard', 'web')),
];
