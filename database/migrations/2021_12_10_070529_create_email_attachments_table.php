<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_attachments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('email_id')->unsigned();
            $table->string('original_name');
            $table->string('name');
            $table->string('image_path');
            $table->timestamps();

            $table->foreign('email_id')
                ->references('id')
                ->on('emails')
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
        Schema::table('email_attachments', function (Blueprint $table) {
            $table->dropForeign('email_attachments_email_id_foreign');
        });
        Schema::dropIfExists('email_attachments');
    }
}
