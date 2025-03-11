# Laravel-OperationLog
> 一个记录模型操作日志的扩展

### 一、安装
```
composer require tiacx/laravel-operation-log
```

### 二、发布配置
```
php artisan vendor:publish --provider="Tiacx\OperationLog\OperationLogProvider"
```

### 三、快速开始

在 `Model` 中添加 `HasOperationLog` 特征即可：

```php
use Tiacx\OperationLog\Models\Traits\HasOperationLog;

class Post extends Model
{
    use HasOperationLog;
}
```

现在，每当您创建、更新或删除 `Post` 模型的记录时，都会在 'operation_logs' 表中创建一个操作日志。

### 四、指定记录的属性

在 `Model` 中添加 `$logAttributes` 属性或 `getLogAttributes()` 方法，以指定需要记录的属性：

```php
use Tiacx\OperationLog\Models\Traits\HasOperationLog;

class Post extends Model
{
    use HasOperationLog;
    
    public $logAttributes = ['title', 'content'];
    
    public function getLogAttributes()
    {
        return ['title', 'content'];
    }
}
```

### 五、手动写入日志

通过使用 `OperationLogHelper::createLog()` 方法，您可以手动写入日志：

```php
use Tiacx\OperationLog\Helpers\OperationLogHelper;

OperationLogHelper::createLog($post, 'custom', [
    'content' => "日志内容",
    'post_id' => $ware->id,
    'post_name' => $ware->name,
]);
```

注：手动写日志时，`$operationType` 固定为 `custom`，且日志内容必须包含 `content` 键。其他键值对可根据实际情况添加。

### 六、自定义配置

您可以修改 `config/operation-log.php` 文件中的配置，以自定义日志记录。

```php
<?php

return [
    'table_name' => env('OPERATION_LOG_TABLE_NAME', 'operation_logs'),
    'connection' => env('OPERATION_LOG_DB_CONNECTION', 'default'),
    'user_model' => env('OPERATION_LOG_USER_MODEL', 'App\Models\User'),
    'guard' => env('OPERATION_LOG_GUARD', env('AUTH_GUARD', 'web')),
];
```

- `table_name`：操作日志表的名称，默认值为 `operation_logs`。
- `connection`：操作日志表的数据库连接，默认值为 `default`。
- `user_model`：操作日志记录的用户模型，默认值为 `App\Models\User`。
- `guard`：操作日志记录的用户守护，默认值为 `web`。

注：如需修改日志表名，除修改配置文件中的 `table_name` 值外，还需要手动修改数据库迁移文件。
