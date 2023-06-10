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
    if (!Schema::hasTable('submitted_works')) {
      Schema::create('submitted_works', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('jahadi_groups_id')->nullable();
        $table->unsignedBigInteger('individuals_id')->nullable();
        $table->unsignedBigInteger('groups_id')->nullable();
        $table->string('attachment_type');
        $table->string('description')->nullable();
        $table->string('file_path');
        $table->foreign('jahadi_groups_id')
          ->references('id')
          ->on('jahadi_groups')
          ->cascadeOnDelete();
        $table->foreign('individuals_id')
          ->references('id')
          ->on('individuals')
          ->cascadeOnDelete();
        $table->foreign('groups_id')
          ->references('id')
          ->on('groups')
          ->cascadeOnDelete();
        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('submitted_works');
  }
};
