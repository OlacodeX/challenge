<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('sellers', 'vat_or_registration_number')) {
            Schema::table('sellers', function (Blueprint $table) {
                $table->renameColumn('vat_or_registration_number', 'vat_number');
                $table->string('registration_number')->nullable()->after('vat_number');
            });
        }
    }

    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->renameColumn('vat_number', 'vat_or_registration_number');
            $table->dropColumn('registration_number');
        });
    }
};
