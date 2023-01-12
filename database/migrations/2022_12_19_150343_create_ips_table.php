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
        Schema::create('ips', function (Blueprint $table) {
            $table->id();

            $table->string('ip')->comment('IP 地址')->index();

            $table->string('mac')->comment('MAC 地址')->index();

            $table->string('netmask')->comment('子网掩码')->index();

            $table->string('gateway')->comment('网关')->index();

            $table->json('nameservers')->comment('DNS 服务器');

            $table->string('interface')->comment('网卡')->index();

            $table->unsignedBigInteger('ip_host_id')->index();

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
        Schema::dropIfExists('ips');
    }
};
