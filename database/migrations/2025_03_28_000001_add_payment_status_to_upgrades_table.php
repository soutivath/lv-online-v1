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
        Schema::table('upgrades', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'success'])->default('pending')->after('date');
            $table->string('payment_proof')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('upgrades', function (Blueprint $table) {
            $table->dropColumn('payment_status');
            $table->dropColumn('payment_proof');
        });
    }
};
