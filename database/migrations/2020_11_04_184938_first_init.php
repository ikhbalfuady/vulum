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
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('menu_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->tinyInteger('active')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

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
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps(0);
            $table->softDeletes('deleted_at');
        });

        
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps(0);
            $table->softDeletes('deleted_at');
        });

        
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->unsignedBigInteger('permission_id')->index();
            $table->unsignedBigInteger('role_id')->index();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps(0);
            $table->softDeletes('deleted_at');
        });

        
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->string('code')->nullable();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps(0);
            $table->softDeletes('deleted_at');
        });

        
        Schema::create('menu_items', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->string('name');
            $table->string('slug')->index();
            $table->text('icon')->nullable();
            $table->text('path')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps(0);
            $table->softDeletes('deleted_at');
        });

        
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->unsignedBigInteger('parent_id')->index()->nullable();
            $table->unsignedBigInteger('menu_item_id')->index();
            $table->unsignedBigInteger('master_menu_id')->index();
            $table->string('overline')->nullable();
            $table->integer('ordering')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps(0);
            $table->softDeletes('deleted_at');
        });

        
        Schema::create('master_menus', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->string('name');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps(0);
            $table->softDeletes('deleted_at');
        });

        
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->boolean('is_read')->default(false);
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->string('link_path')->nullable();
            $table->json('link_params')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps(0);
            $table->softDeletes('deleted_at');
        });

        DB::statement('ALTER TABLE 
        user_notifications ADD FULLTEXT 
        user_notifications_fulltext(title,description)
        ');

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
        Schema::dropIfExists('role_permissions'); 
        Schema::dropIfExists('roles'); 
        Schema::dropIfExists('menu_items'); 
        Schema::dropIfExists('menus'); 
        Schema::dropIfExists('master_menus'); 

    }
}        
        