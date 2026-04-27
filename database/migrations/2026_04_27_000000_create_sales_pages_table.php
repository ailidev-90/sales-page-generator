<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->text('description');
            $table->text('key_features')->nullable();
            $table->string('target_audience');
            $table->string('price')->nullable();
            $table->text('unique_selling_points')->nullable();
            $table->string('tone')->nullable();
            $table->string('template')->nullable();
            $table->json('generated_content');
            $table->timestamps();

            $table->index(['user_id', 'product_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_pages');
    }
};
