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
    Schema::create('groups', function (Blueprint $table) {
      $table->id()->autoIncrement();
      $table->string('group_name');
      $table->integer('established_year')->nullable();
      $table->string('group_license_number')->nullable();
      $table->string('group_institution');
      $table->string('group_city');
      $table->string('group_supervisor_fname');
      $table->string('group_supervisor_lname');
      $table->string('group_supervisor_national_code');
      $table->string('phone_number');
      $table->string('current_verify_code')->nullable();
      $table->integer('verify_code_count')->default(0);
      $table->string('last_ip')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('groups');
  }
};
