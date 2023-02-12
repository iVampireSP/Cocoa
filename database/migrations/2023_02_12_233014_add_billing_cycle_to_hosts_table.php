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
    public function up(): void
    {
        Schema::table('hosts', function (Blueprint $table) {
            $table->enum('billing_cycle', [
                'monthly',
                'quarterly',
                'semi-annually',
                'annually',
                'biennially',
                'triennially',
            ])->nullable()->index()->after('status');

            $table->dateTime('next_due_at')->nullable()->after('billing_cycle')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('hosts', function (Blueprint $table) {
            $table->dropColumn('billing_cycle');
            $table->dropColumn('next_due_at');
        });
    }
};
