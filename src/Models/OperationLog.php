<?php

namespace Tiacx\OperationLog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OperationLog extends Model
{
    protected $table = 'operation_logs';

    public $incrementing = true;

    public $timestamps = true;

    public $casts = [
        'operation_content' => 'array',
    ];

    public $fillable = [
        'id',
        'loggable_type',
        'loggable_id',
        'operation_type',
        'operation_content',
        'ua',
        'ip',
        'operator_id',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('operation-log.table_name'));
        $this->setConnection(config('operation-log.connection'));
    }

    public function operator(): HasOne
    {
        return $this->hasOne(config('operation-log.user_model'), 'id', 'operator_id');
    }
}
