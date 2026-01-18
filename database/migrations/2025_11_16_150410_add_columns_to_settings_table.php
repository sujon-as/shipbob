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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('min_ratings', 50)->default('4')->nullable()->after('maintain_desc_text');
            $table->string('order_success_mgs_1', 191)->default('Review submitted successfully.')->nullable()->after('maintain_desc_text');
            $table->string('order_success_mgs_2', 191)->default('Review submitted successfully & complete the task.')->nullable()->after('maintain_desc_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
};
