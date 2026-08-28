<?php

namespace Database\Seeders;

use App\Models\Audit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AuditSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        Audit::truncate();

        Schema::enableForeignKeyConstraints();
    }
}
