<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $this->upDocuments();
        $this->upDocumentFiles();
    }

    private function upDocuments(): void
    {
        Schema::create('client_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->string('number');
            $table->string('description')->nullable();
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('status');
            $table->date('date_start');
            $table->date('date_end');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function upDocumentFiles(): void
    {
        Schema::create('client_document_files', function (Blueprint $table) {
            $table->char('guid', 32)->primary();
            $table->unsignedInteger('document_id');

            $table->foreign('document_id')
                ->references('id')
                ->on('client_documents')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('guid')
                ->references('guid')
                ->on('files')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_document_files');
        Schema::dropIfExists('client_documents');
    }
};
