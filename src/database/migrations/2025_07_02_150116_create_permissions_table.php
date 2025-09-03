<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(table: 'permissions',
            callback: function (Blueprint $table): void {
                $table->uuid(column: 'id')->primary();

                $table->string(column: 'name', length: 40);
                $table->string(column: 'slug', length: 40)->unique();

                $table->enum(
                    column: 'guard',
                    allowed: ['api', 'web']
                )->default(
                    value: 'api'
                );

                $table->timestamps(precision: 6);
            }
        );

        Schema::table(table: 'permissions',
            callback: function (Blueprint $table): void {
                $table->comment(comment: 'Права');
                
                $table->index(columns: ['guard']);
                $table->index(columns: 'created_at');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'permissions');
    }
};
