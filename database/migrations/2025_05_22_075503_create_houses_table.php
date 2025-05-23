<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousesTable extends Migration
{
    public function up()
    {
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->decimal('price', 15, 2); // Harga dengan 2 desimal
            $table->string('status')->default('Tersedia');
            $table->string('photo_path')->nullable();
            $table->text('description')->nullable()->after('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('houses');
    }
}