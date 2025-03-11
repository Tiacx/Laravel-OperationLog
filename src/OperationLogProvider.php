<?php

namespace Tiacx\OperationLog;

use Illuminate\Support\ServiceProvider;

class OperationLogProvider extends ServiceProvider
{
    /**
     * 引导包服务
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/config/operation-log.php' => config_path('operation-log.php'),
        ], 'config');

        $this->publishes([
            dirname(__DIR__) . '/migrations/create_operation_logs_table.php' => database_path('migrations/'.  date('Y_m_d_His') .'_create_operation_logs_table.php'),
        ], 'migrations');
    }
}
