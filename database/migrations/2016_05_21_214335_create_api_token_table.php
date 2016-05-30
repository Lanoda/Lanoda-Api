<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Notes
        Schema::create('api_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('api_token', 32)->unique();
            $table->string('client_id');
            $table->integer('user_id')->unsigned();
            $table->timestamp('expires');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('client_id')
                  ->references('client_id')->on('api_clients')
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
        // Drop Notes
        if (Schema::hasTable('api_tokens'))
        {
            Schema::drop('api_tokens');
        }
    }

}
