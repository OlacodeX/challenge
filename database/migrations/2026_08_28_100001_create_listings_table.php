<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->integer('seller_id')->index();
            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('category')->index();
            $table->integer('price');
            $table->string('currency')->index();
            $table->string('country')->index();
            $table->string('city')->index();
            $table->date('date_online');
            $table->date('date_offline');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
