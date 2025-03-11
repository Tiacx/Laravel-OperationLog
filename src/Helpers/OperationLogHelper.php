<?php

namespace Tiacx\OperationLog\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Tiacx\OperationLog\Models\OperationLog;

class OperationLogHelper
{
    public static function getLogAttributes(Model $model): array
    {
        if (method_exists($model, 'getLogAttributes')) {
            return $model->getLogAttributes();
        } elseif (property_exists($model, 'logAttributes')) {
            return $model->logAttributes;
        } else {
            return array_keys($model->getAttributes());
        }
    }

    public static function handleCreatedEvent(Model $model): Model
    {
        return static::createLog($model, 'create', [
            'created' => Arr::only($model->toArray(), static::getLogAttributes($model)),
        ]);
    }

    public static function handleUpdatedEvent(Model $model): Model
    {
        $attributes = static::getLogAttributes($model);

        return static::createLog($model, 'update', [
            'original' => Arr::only($model->getOriginal(), $attributes),
            'updated' => Arr::only($model->toArray(), $attributes),
        ]);
    }

    public static function handleDeletedEvent(Model $model): Model
    {
        return static::createLog($model, 'delete', [
            'deleted' => Arr::only($model->getOriginal(), static::getLogAttributes($model)),
        ]);
    }

    public static function createLog(Model $model, string $operationType, array $operationContent): Model
    {
        $guard = config('operation-log.guard');

        if (!in_array($operationType, ['create', 'update', 'delete', 'custom'])) {
            abort(400, 'Invalid operation type');
        }

        if ($operationType == 'custom' && !isset($operationContent['content'])) {
            abort(400, 'Invalid operation content');
        }

        return OperationLog::query()->create([
            'loggable_type' => get_class($model),
            'loggable_id' => $model->getKey(),
            'operation_type' => $operationType,
            'operation_content' => $operationContent,
            'ua' => request()->userAgent(),
            'ip' => request()->ip(),
            'operator_id' => auth($guard)->id(),
        ]);
    }
}
