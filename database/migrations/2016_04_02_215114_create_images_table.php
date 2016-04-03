<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Contact;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Images
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('url');
            $table->string('mime_type');
            $table->string('size');
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
        // Drop images
        if (Schema::hasTable('contacts'))
        {
            Schema::drop('contacts');
        }
        if (Schema::hasTable('users'))
        {
            Schema::drop('users');
        }
        if (Schema::hasTable('images'))
        {
            Schema::drop('images');
        }
    }
}
