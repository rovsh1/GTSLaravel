<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $this->upGroups();
        $this->upMembers();
        $this->upRules();
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

            $table->foreign('administrator_id')
                ->references('id')
                ->on('administrators')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('group_id')
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

            $table->primary(['group_id', 'resource', 'permission']);

            $table->foreign('group_id')
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
    }
};
