<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_checks', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->nullable();
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('bot')->nullable();
            $table->string('verified')->nullable();
            $table->string('premium')->nullable();
            $table->string('temp')->nullable();
            $table->string('exists')->nullable();
            $table->string('error')->nullable();
            $table->json('api_response')->nullable();

            $table->string('has_telegram')->default(false)->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_checks');
    }
};
