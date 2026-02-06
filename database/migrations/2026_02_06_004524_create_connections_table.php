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
        Schema::create('connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id_low');
            $table->foreignId('user_id_high');
            $table->unique(['user_id_low', 'user_id_high']);
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->enum('action_user', ['low', 'high'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connections');
    }
};
