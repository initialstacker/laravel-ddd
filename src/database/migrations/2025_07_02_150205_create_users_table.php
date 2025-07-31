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
        Schema::create(table: 'users',
            callback: function (Blueprint $table): void {
                $table->uuid(column: 'id')->primary();

                $table->string(column: 'name', length: 35);
                $table->string(column: 'email', length: 244)->unique();
                $table->timestamp(column: 'email_verified_at')->nullable();
                $table->uuid(column: 'role_id')->nullable();
                $table->string(column: 'password', length: 60);

                $table->rememberToken();
                $table->timestamps(precision: 6);
            }
        );

        Schema::table(table: 'users',
            callback: function (Blueprint $table): void {
                $table->comment(comment: 'Пользователи');

                $table->foreign(columns: 'role_id')
                    ->references(columns: 'id')
                    ->on(table: 'roles')
                    ->nullOnDelete();

                $table->index(columns: 'role_id');
                $table->index(columns: 'created_at');
            }
        );

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'users');
        Schema::dropIfExists('sessions');
    }
};
