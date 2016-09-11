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
        $this->upUsers();
        $this->upPasswordResets();
        $this->upContacts();
        $this->upTags();
        $this->upImages();
        $this->upContactTypes();
        $this->upNotes();
        $this->upNoteTypes();
        $this->upRoles();
        $this->upRoleUser();
        $this->upApiClients();
        $this->upApiTokens();

        $this->setForeignKeys();
    }

    private function upUsers()
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
    }

    private function upPasswordResets()
    {
        // Password Resets
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });
    }

    private function upContacts()
    {
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
    }

    private function upTags()
    {
        // Tags
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('color');
            $table->timestamps();
        });
    }

    private function upImages()
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

    private function upContactTypes()
    {
        // ContactTypes
        Schema::create('contact_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description');
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    private function upNotes()
    {
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
    }

    private function upNoteTypes()
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

    private function upRoles()
    {
        // Roles
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
    }

    private function upRoleUser()
    {
        // RoleUser
        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->primary(array('role_id', 'user_id'));
        });
    }

    private function upApiClients()
    {
        // Api Clients
        Schema::create('api_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id')->unique();
            $table->string('client_secret', 32);
            $table->timestamps();
        });
    }

    private function upApiTokens()
    {
        // Api Tokens
        Schema::create('api_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('api_token', 32)->unique();
            $table->string('refresh_token', 48)->unique();
            $table->string('client_id');
            $table->integer('user_id')->unsigned();
            $table->dateTime('expires');
            $table->timestamps();
        });
    }

    private function setForeignKeys()
    {
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
        $tables = Array(
            'tags',
            'notes',
            'note_types',
            'contacts',
            'role_user',
            'api_tokens',
            'users',
            'api_clients',
            'contact_types',
            'password_resets',
            'images',
            'roles',
        );

        foreach($tables as $table) {
            $this->DropIfHasTable($table);
        }
    }

    // Drop table if Schema has it.
    private function DropIfHasTable($table)
    {
        if (Schema::hasTable($table)) {
            Schema::drop($table);
        }
    }
}
