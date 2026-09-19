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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->autoIncrement();
            $table->uuid('uuid')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('last_name', 50)->nullable();
            $table->string('first_name', 50)->nullable();
            $table->date('birthday')->nullable();
            $table->integer('gender')->default(0);
            $table->string('phone', 20)->nullable();
            $table->string('address', 150)->nullable();
            $table->string('user_type', 20)->nullable();
            $table->string('customer_id', 20)->nullable();
            $table->integer('status')->default(1)->comment("1: Active 0: Blocked");;
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('user_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
