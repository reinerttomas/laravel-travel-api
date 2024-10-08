<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignUuid('role_id')->constrained();
            $table->foreignUuid('user_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};
