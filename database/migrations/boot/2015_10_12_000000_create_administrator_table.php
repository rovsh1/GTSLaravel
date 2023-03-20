<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $this->upUsers();
        $this->upGroups();
        $this->upMembers();
        $this->upRules();
    }

    private function upUsers()
    {
        Schema::create('administrators', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->smallInteger('post_id')->unsigned()->nullable();
            $table->string('presentation', 100);
            $table->string('name', 100)->nullable();
            $table->string('surname', 100)->nullable();
            $table->tinyInteger('gender')->unsigned()->nullable();
            $table->string('login', 50)->nullable();
            $table->char('password', 60)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->tinyInteger('superuser')->unsigned()->default(0);
            $table->timestamps();

            $table->foreign('post_id', 'fkey_post_id')
                ->references('id')
                ->on('r_enums')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function upGroups()
    {
        Schema::create('administrator_access_groups', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->string('name', 100);
            $table->string('description', 255);
        });
    }

    private function upMembers()
    {
        Schema::create('administrator_access_members', function (Blueprint $table) {
            $table->integer('administrator_id')->unsigned();
            $table->smallInteger('group_id')->unsigned();

            $table->primary(['administrator_id', 'group_id']);

            $table->foreign('administrator_id', 'administrator_access_members_fkey_administrator_id')
                ->references('id')
                ->on('administrators')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('group_id', 'administrator_access_members_fkey_group_id')
                ->references('id')
                ->on('administrator_access_groups')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function upRules()
    {
        Schema::create('administrator_access_rules', function (Blueprint $table) {
            $table->smallInteger('group_id')->unsigned();
            $table->string('resource', 100);
            $table->string('permission', 25);
            $table->tinyInteger('flag')->unsigned();

            $table->unique(['group_id', 'resource', 'permission'], 'administrator_access_rules_unq_group_id');

            $table->foreign('group_id', 'administrator_access_rules_fkey_group_id')
                ->references('id')
                ->on('administrator_access_groups')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('administrator_access_rules');
        Schema::dropIfExists('administrator_access_members');
        Schema::dropIfExists('administrator_access_groups');
        Schema::dropIfExists('administrators');
    }
};
