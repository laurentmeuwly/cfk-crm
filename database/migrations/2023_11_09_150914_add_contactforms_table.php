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
        Schema::create('contactforms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('prefered_language', 2);
            $table->boolean('newsletter')->default(false);
            $table->boolean('agreement')->default(false);
            $table->text('message');
            $table->unsignedBigInteger('title_id');
            $table->unsignedBigInteger('source_id');

            $table->timestamps();

            $table->foreign('title_id')->references('id')->on('titles');
            $table->foreign('source_id')->references('id')->on('sources');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('contactforms');
    }
};
