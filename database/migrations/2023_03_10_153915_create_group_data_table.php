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
        Schema::table('group_data', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('group_name');
            $table->string('group_registeration_number');
            $table->string('group_register_date');
            $table->string('group_nature');
            $table->string('subset_nature');
            $table->string('group_state');
            $table->string('group_city');
            $table->string('group_activity_state');
            $table->string('group_activity_city');
            $table->integer('group_established_year');
            $table->string('group_landline_number');
            $table->string('group_supervisor_fullname');
            $table->string('group_supervisor_phone');
            $table->string('group_supervisor_national_code');
            $table->string('group_supervisor_birth_date');
            $table->string('group_supervisor_birth_certificate_number');
            $table->string('group_supervisor_father_name');
            $table->integer('group_start_activity_year');
            $table->boolean('is_agriculture');
            $table->boolean('is_cultural');
            $table->boolean('is_educational');
            $table->boolean('is_healthcare');
            $table->boolean('is_economic');
            $table->boolean('is_construction');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_data');
    }
};
