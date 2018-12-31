<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->increments('pid');
            $table->string('animal', 100)->default('dog')->index();
            $table->string('name', 255);
            $table->string('breed', 100)->index();
            $table->string('size', 20);
            $table->string('sex', 15);
            $table->string('address', 255);
            $table->string('city', 255);
            $table->string('state', 5);
            $table->string('zip', 10);
            $table->string('phone', 20);
            $table->string('email', 255);
            $table->text('description');
            $table->string('status', 2)->default('A')->index();
            $table->tinyInteger('mix')->default(0)->index();
            $table->string('shelterid', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pets');
    }
}
