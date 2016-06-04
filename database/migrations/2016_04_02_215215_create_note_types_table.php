<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoteTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // NoteTypes
        Schema::create('note_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->string('icon')->nullable();
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
        // Drop NoteTypesx
        if (Schema::hasTable('notes')) 
        {
            Schema::drop('notes');
        }
        if (Schema::hasTable('note_types')) 
        {
            Schema::drop('note_types');
        }
    }
}
