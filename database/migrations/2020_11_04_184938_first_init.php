<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FirstInit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

                Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->string('name');
            $table->string('username');
            $table->string('password');
            $table->string('email');
            $table->text('picture')->nullable();
            $table->tinyInteger('active')->default(0);

            $table->timestamps(0);
            $table->softDeletes('deleted_at');
        });

        
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->text('token');
            $table->string('ip')->nullable();
            $table->string('agent')->nullable();
            $table->string('platform')->nullable();

            $table->timestamps(0);
            $table->softDeletes('deleted_at');
        });

        
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('prefix_group')->nullable();

            $table->timestamps(0);
            $table->softDeletes('deleted_at');
        });

        
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->unsignedBigInteger('permission_id')->index();
            $table->string('name');
            $table->string('slug')->nullable();

            $table->timestamps(0);
            $table->softDeletes('deleted_at');
        });

        
        Schema::create('user_roles', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('role_id')->index();

            $table->timestamps(0);
            $table->softDeletes('deleted_at');
        });

        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            Schema::dropIfExists('users'); 
        Schema::dropIfExists('user_sessions'); 
        Schema::dropIfExists('permissions'); 
        Schema::dropIfExists('roles'); 
        Schema::dropIfExists('user_roles'); 

    }
}        
        