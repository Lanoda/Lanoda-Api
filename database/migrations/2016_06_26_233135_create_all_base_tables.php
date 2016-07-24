<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllBaseTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Users
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->integer('image_id')->unsigned()->nullable();
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Password Resets
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });

        // Contacts
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('image_id')->unsigned()->nullable();
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('lastname')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->integer('age')->nullable();
            $table->date('birthday')->nullable();
            $table->timestamps();
        });

        // Tags
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('color');
            $table->timestamps();
        });
        
        // Images
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('url');
            $table->string('mime_type');
            $table->string('size');
            $table->timestamps();
        });
        
        // ContactTypes
        Schema::create('contact_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description');
            $table->string('icon')->nullable();
            $table->timestamps();
        });
        
        // Notes
        Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('contact_id')->unsigned()->nullable();
            $table->integer('type_id')->unsigned();
            $table->string('title');
            $table->string('body');
            $table->timestamps();
        });
        
        // NoteTypes
        Schema::create('note_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // Roles
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        // RoleUser
        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->primary(array('role_id', 'user_id'));
        });

        // Api Clients
        Schema::create('api_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id')->unique();
            $table->string('client_secret', 32);
            $table->timestamps();
        });

        // Api Tokens
        Schema::create('api_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('api_token', 32)->unique();
            $table->string('client_id');
            $table->integer('user_id')->unsigned();
            $table->dateTime('expires');
            $table->timestamps();
        });


        // ***********************************
        // * Add Foreign Keys
        // ***********************************
        
        // Users
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('image_id')
                  ->references('id')->on('images')
                  ->onDelete('cascade');
        });

        // Contacts
        Schema::table('contacts', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('image_id')
                  ->references('id')->on('images')
                  ->onDelete('cascade');
        });

        // Notes
        Schema::table('notes', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('contact_id')
                  ->references('id')->on('contacts')
                  ->onDelete('cascade');

            $table->foreign('type_id')
                  ->references('id')->on('note_types')
                  ->onDelete('cascade');
        });

        // Api Tokens
        Schema::table('api_tokens', function (Blueprint $table) {
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

        // Tags
        if (Schema::hasTable('tags'))
        {
            Schema::drop('tags');
        }

        // Drop Notes
        if (Schema::hasTable('notes')) 
        {
            Schema::drop('notes');
        }

        // Drop NoteTypes
        if (Schema::hasTable('note_types')) 
        {
            Schema::drop('note_types');
        }

        // Drop Contacts
        if (Schema::hasTable('contacts'))
        {
            Schema::drop('contacts');
        }

        // Drop RoleUser
        if (Schema::hasTable('role_user')) 
        {
            Schema::drop('role_user');
        }

        // Drop ApiTokens
        if (Schema::hasTable('api_tokens'))
        {
            Schema::drop('api_tokens');
        }

        // Drop Users
        if (Schema::hasTable('users')) 
        {
            Schema::drop('users');
        }
        
        // Drop ApiClients
        if (Schema::hasTable('api_clients'))
        {
            Schema::drop('api_clients');
        }

        // ContactTypes
        if (Schema::hasTable('contact_types'))
        {
            Schema::drop('contact_types');
        }

        // Drop PasswordResets
        if (Schema::hasTable('password_resets'))
        {
            Schema::drop('password_resets');
        }

        // Images
        if (Schema::hasTable('images'))
        {
            Schema::drop('images');
        }

        // Drop Roles
        if (Schema::hasTable('roles'))
        {
            Schema::drop('roles');
        }
    }
}
