<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_visits', function (Blueprint $table): void {
            $table->id();
            $table->string('page');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('session_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('device_type', 20)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('visited_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_visits');
    }
};
