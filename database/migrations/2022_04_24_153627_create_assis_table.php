<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('assis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collection_id');
            $table->string('name')->nullable();
            $table->integer('total');
            $table->enum('type', ['anime', 'cartoon', 'dorama', 'movie', 'serie', 'special', 'specials', 'youtube', 'other']);
            $table->enum('status', ['assistindo', 'para_assistir', 'desistido', 'completo', 'pausado']);
            $table->boolean('airing')->default(false);
            $table->enum('weekday', ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])->nullable();
            $table->integer('order');
            $table->boolean('hidden_collection')->default(false);
            $table->string('image')->nullable();
            $table->integer('average_time')->nullable();
            $table->integer('year')->nullable();
            $table->text('sinopse')->nullable();
            $table->timestamps();

            $table->foreign('collection_id')->references('id')->on('assis_collections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('assis');
    }
}
