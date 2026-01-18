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
        Schema::create('vip_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vip_id')->nullable()->constrained('levels')->onDelete('cascade');
            $table->text('upgrade_text')->nullable();
            $table->integer('progress_in_percentage')->default(0); // 0 - 100
            $table->text('showing_amount_text')->nullable();
            $table->text('authority_text')->nullable();
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
        Schema::dropIfExists('vip_details');
    }
};
