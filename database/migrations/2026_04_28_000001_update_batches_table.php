<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            if (! Schema::hasColumn('batches', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (! Schema::hasColumn('batches', 'start_date')) {
                $table->date('start_date')->nullable()->after('description');
            }
            if (! Schema::hasColumn('batches', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn(['description', 'start_date', 'end_date']);
        });
    }
};
