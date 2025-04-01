<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->unique()->after('email');
            $table->string('photo')->nullable()->after('phone');
            $table->foreignId('position_id')->constrained('positions')->onDelete('cascade')->after('photo');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'photo']);
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');
        });
    }
};
