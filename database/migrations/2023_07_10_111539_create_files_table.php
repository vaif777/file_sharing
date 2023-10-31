<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->text('content')->nullable();
            $table->string('href');
            $table->string('slug')->unique();
            $table->string('original_name');
            $table->string('type');
            $table->string('file_extension');
            $table->integer('file_size')->unsigned();
            $table->tinyInteger('access_id')->unsigned();
            $table->tinyInteger('user_id')->unsigned();
            $table->boolean('pin')->default(false);
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
        Schema::dropIfExists('files');
    }
};
