<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('operation_logs', function (Blueprint $table) {
            $table->comment('操作日志表');
            $table->id()->comment('主键ID');
            $table->string('loggable_type', 100)->index()->comment('操作对象类型');
            $table->string('loggable_id', 36)->index()->comment('操作对象ID');
            $table->string('operation_type', 50)->index()->comment('操作类型');
            $table->json('operation_content')->comment('操作内容');
            $table->string('ua', 255)->comment('User-Agent');
            $table->string('ip', 50)->comment('IP地址');
            $table->string('operator_id', 36)->index()->comment('操作人ID');
            $table->datetimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_logs');
    }
};
