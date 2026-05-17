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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('remember_token');
            $table->text('bio')->nullable()->after('avatar');          // giới thiệu sơ lược
            $table->string('interests')->nullable()->after('bio');     // sở thích (có thể lưu JSON hoặc text)
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('interests');
            $table->date('dob')->nullable()->after('gender');          // ngày sinh
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'bio', 'interests', 'gender', 'dob']);
        });
    }
};
