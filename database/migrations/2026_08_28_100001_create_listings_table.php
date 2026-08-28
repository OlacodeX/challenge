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
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('category')->index();
            $table->integer('price')->index();
            $table->string('currency');
            $table->string('country')->index();
            $table->string('city');
            $table->date('date_online');
            $table->date('date_offline');
            $table->string('status');
            $table->timestamps();

            $table->index(['status', 'date_online', 'date_offline']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
