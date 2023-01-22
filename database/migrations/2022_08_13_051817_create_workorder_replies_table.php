<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_order_replies', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->index();

            // content
            $table->text('content');

            // work_order id (on delete cascade)
            $table->unsignedBigInteger('work_order_id')->index();
            $table->foreign('work_order_id')->references('id')->on('work_orders')->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            $table->boolean('is_pending')->default(false)->index();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_replies');
    }
};
