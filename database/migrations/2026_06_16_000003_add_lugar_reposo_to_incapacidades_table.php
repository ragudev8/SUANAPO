<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incapacidades', function (Blueprint $table) {
            $table->string('lugar_reposo', 20)->default('casa')->after('dias_reposo');
        });
    }

    public function down(): void
    {
        Schema::table('incapacidades', function (Blueprint $table) {
            $table->dropColumn('lugar_reposo');
        });
    }
};
