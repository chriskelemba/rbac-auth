<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->date('added_on')->nullable();
            $table->date('ended_on')->nullable();
            $table->timestamps();
            $table->unique(['permission_id', 'role_id']);
        });

        DB::table('permission_role')->update([
            'added_on' => now()->toDateString(),
        ]);

        Schema::table('permission_role', function (Blueprint $table) {
            $table->date('added_on')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_role');
    }
};
