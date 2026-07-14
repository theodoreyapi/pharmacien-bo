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
        Schema::table('rappels', function (Blueprint $table) {
            $table->string('provider_id')->nullable()->after('status');   // resource_id Orange / message id WhatsApp
            $table->text('error_message')->nullable()->after('provider_id');
        });
    }

    public function down(): void
    {
        Schema::table('rappels', function (Blueprint $table) {
            $table->dropColumn(['provider_id', 'error_message']);
        });
    }
};
