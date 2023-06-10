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
    Schema::table('submitted_works', function (Blueprint $table) {
      $table->after('file_path', function ($table) {
        $table->integer('score1')->default(0)->nullable(false);
        $table->string('score1_description')->nullable(true);
        $table->integer('score2')->default(0)->nullable(false);
        $table->string('score2_description')->nullable(true);
        $table->integer('score3')->default(0)->nullable(false);
        $table->string('score3_description')->nullable(true);
        $table->timestamp('deleted_at')->nullable();
      });
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('submitted_works', function (Blueprint $table) {
      //
    });
  }
};
