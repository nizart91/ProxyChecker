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
        Schema::create('proxies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks');

            $table->string('ip'); // IP-адрес прокси-сервера
            $table->unsignedTinyInteger('proxy_type')->default(null)->nullable(); // Тип прокси (http, https, socks4, socks5)
            $table->timestamp('started_at')->default(null)->nullable();
            $table->timestamp('finished_at')->default(null)->nullable();
            $table->string('location')->default(null)->nullable(); // Страна/город
            $table->boolean('status')->default(null)->nullable(); // Статус
            $table->unsignedSmallInteger('timeout')->default(null)->nullable(); // скорость скачивания через прокси или таймаут прокси
            $table->string('real_ip')->default(null)->nullable(); //реальный внешний ip прокси
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proxies');
    }
};
