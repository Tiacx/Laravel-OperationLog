<?php

namespace Tiacx\OperationLog\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tiacx\OperationLog\Helpers\OperationLogHelper;
use Tiacx\OperationLog\Models\OperationLog;

trait HasOperationLog
{
    public function operationLogs(): HasMany
    {
        return $this->hasMany(OperationLog::class, 'loggable_id', 'id');
    }

    public function getLogAttributes(): array
    {
        return $this->logAttributes ?? array_keys($this->getAttributes());
    }

    /**
     * 操作日志
     * @return void
     */
    public static function bootHasOperationLog()
    {
        $guard = config('operation-log.guard');

        static::created(function (Model $model) use ($guard) {
            if (auth($guard)->check()) {
                OperationLogHelper::handleCreatedEvent($model);
            }
        });

        static::updated(function ($model) use ($guard) {
            if (auth($guard)->check()) {
                OperationLogHelper::handleUpdatedEvent($model);
            }
        });

        static::deleted(function ($model) use ($guard) {
            if (auth($guard)->check()) {
                OperationLogHelper::handleDeletedEvent($model);
            }
        });
    }
}
