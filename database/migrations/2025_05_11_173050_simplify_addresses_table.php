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
        Schema::table('addresses', function (Blueprint $table) {
            // Remove existing columns
            $table->dropColumn(['street', 'city', 'state', 'country', 'postal_code']);
            
            // Add simple address column
            $table->text('address')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('address');
            
            // Re-add original columns if rolling back
            $table->string('street');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('postal_code');
        });
    }
};
