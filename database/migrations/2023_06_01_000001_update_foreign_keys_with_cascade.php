<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Modify students-related tables
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });

        Schema::table('upgrades', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });

        // Modify major-related tables
        Schema::table('registration_details', function (Blueprint $table) {
            $table->dropForeign(['major_id']);
            $table->foreign('major_id')->references('id')->on('majors')->onDelete('cascade');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['major_id']);
            $table->foreign('major_id')->references('id')->on('majors')->onDelete('cascade');
        });

        Schema::table('upgrades', function (Blueprint $table) {
            $table->dropForeign(['major_id']);
            $table->foreign('major_id')->references('id')->on('majors')->onDelete('cascade');
        });

        // Modify subject-related tables
        Schema::table('upgrade_details', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });

        // Modify registration-related tables
        Schema::table('registration_details', function (Blueprint $table) {
            $table->dropForeign(['registration_id']);
            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
        });

        // Modify upgrade-related tables
        Schema::table('upgrade_details', function (Blueprint $table) {
            $table->dropForeign(['upgrade_id']);
            $table->foreign('upgrade_id')->references('id')->on('upgrades')->onDelete('cascade');
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Restore students-related tables (set to restrict by default)
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->foreign('student_id')->references('id')->on('students');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->foreign('student_id')->references('id')->on('students');
        });

        Schema::table('upgrades', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->foreign('student_id')->references('id')->on('students');
        });

        // Restore major-related tables
        Schema::table('registration_details', function (Blueprint $table) {
            $table->dropForeign(['major_id']);
            $table->foreign('major_id')->references('id')->on('majors');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['major_id']);
            $table->foreign('major_id')->references('id')->on('majors');
        });

        Schema::table('upgrades', function (Blueprint $table) {
            $table->dropForeign(['major_id']);
            $table->foreign('major_id')->references('id')->on('majors');
        });

        // Restore subject-related tables
        Schema::table('upgrade_details', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->foreign('subject_id')->references('id')->on('subjects');
        });

        // Restore registration-related tables
        Schema::table('registration_details', function (Blueprint $table) {
            $table->dropForeign(['registration_id']);
            $table->foreign('registration_id')->references('id')->on('registrations');
        });

        // Restore upgrade-related tables
        Schema::table('upgrade_details', function (Blueprint $table) {
            $table->dropForeign(['upgrade_id']);
            $table->foreign('upgrade_id')->references('id')->on('upgrades');
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
