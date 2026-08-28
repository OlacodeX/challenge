<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unique();
            $table->string('company_name');
            $table->string('vat_number')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->string('kyb_status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
