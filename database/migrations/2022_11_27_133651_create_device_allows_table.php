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
        Schema::create('device_allows', function (Blueprint $table) {
            $table->id();

            $table->string('type')->index();

            $table->foreignId('device_id')->constrained()->cascadeOnDelete()->index();

            $table->string('topic')->index();

            // 行动
            $table->string('action')->index();

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
        Schema::dropIfExists('device_allows');
    }
};
