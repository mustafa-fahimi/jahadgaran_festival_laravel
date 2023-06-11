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
    if (!Schema::hasTable('scores')) {
      Schema::create('scores', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('referees_id');
        $table->unsignedBigInteger('submitted_works_id');
        $table->integer('score');
        $table->text('description');
        $table->timestamp('deleted_at')->nullable();
        $table->timestamps();
        $table->foreign('referees_id')
          ->references('id')
          ->on('referees')
          ->onDelete('cascade');
        $table->foreign('submitted_works_id')
          ->references('id')
          ->on('submitted_works')
          ->onDelete('cascade');
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('scores');
  }
};
