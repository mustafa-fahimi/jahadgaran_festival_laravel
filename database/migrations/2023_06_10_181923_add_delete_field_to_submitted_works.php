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
      Schema::table('submitted_works', function (Blueprint $table) {
        $table->timestamp('deleted_at')->nullable()->after('file_path');
      });
    }
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
