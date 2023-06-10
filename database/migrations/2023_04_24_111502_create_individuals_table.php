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
    if (!Schema::hasTable('individuals')) {
      Schema::create('individuals', function (Blueprint $table) {
        $table->id()->autoIncrement();
        $table->string('fname');
        $table->string('lname');
        $table->string('city');
        $table->string('national_code');
        $table->string('phone_number');
        $table->string('current_verify_code')->nullable();
        $table->integer('verify_code_count')->default(0);
        $table->string('last_ip')->nullable();
        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('individuals');
  }
};
