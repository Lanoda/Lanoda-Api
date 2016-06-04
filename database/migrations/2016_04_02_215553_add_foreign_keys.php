<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Users
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('image_id')
                  ->references('id')->on('images');
        });

        // Contacts
        Schema::table('contacts', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->foreign('image_id')
                  ->references('id')->on('images');
        });

        // Notes
        Schema::table('notes', function (Blueprint $table) {
            $table->foreign('contact_id')
                  ->references('id')->on('contacts')
                  ->onDelete('cascade');
            $table->foreign('type_id')
                  ->references('id')->on('note_types')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
