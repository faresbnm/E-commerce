<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('product_category', function (Blueprint $table) {
            // Drop existing foreign keys
            $table->dropForeign(['product_id']);
            $table->dropForeign(['category_id']);
            
            // Re-add them with cascade on delete
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
                  
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('product_category', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['category_id']);
            
            // Re-add original constraints without cascade
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products');
                  
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories');
        });
    }
};
