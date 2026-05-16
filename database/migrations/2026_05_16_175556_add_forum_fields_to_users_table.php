<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->string('avatar')->nullable()->after('email');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('university')->nullable()->after('bio');
            $table->string('major')->nullable()->after('university');
            $table->string('year')->nullable()->after('major');
            $table->enum('role', ['student', 'moderator', 'admin'])->default('student')->after('year');
            $table->integer('reputation')->default(0)->after('role');
            $table->timestamp('last_seen_at')->nullable()->after('reputation');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'avatar', 'bio', 'university',
                'major', 'year', 'role', 'reputation', 'last_seen_at'
            ]);
        });
    }
};
