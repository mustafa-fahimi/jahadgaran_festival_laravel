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
            $table->string('current_verify_code')->nullable();
            $table->integer('verify_code_count')->nullable();
            $table->string('last_ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_data', function (Blueprint $table) {
            $table->dropColumn('current_verify_code');
            $table->dropColumn('verify_code_count');
            $table->dropColumn('last_ip');
        });
    }
};
