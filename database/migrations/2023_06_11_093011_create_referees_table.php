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
    if (!Schema::hasTable('referees')) {
      Schema::create('referees', function (Blueprint $table) {
        $table->id();
        $table->string('fname')->nullable(false)->default('داور');
        $table->string('lname')->nullable(false)->default('جشنواره');
        $table->string('phone')->nullable(false);
        $table->string('national_code')->nullable(false)->default('0');
        $table->string('current_verify_code')->nullable();
        $table->timestamp('deleted_at')->nullable();
        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('referees');
  }
};
