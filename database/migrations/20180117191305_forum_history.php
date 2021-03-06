<?php

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

class ForumHistory extends Migration
{
    public function up()  {
        $this->schema->create('forum_histories', function(Blueprint $table){
            $table->increments('id');
            $table->integer('id_message')->unsigned();
            $table->integer('id_user')->unsigned();
            $table->integer('time')->unsigned();
            $table->text('message');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_message')->references('id')->on('forum_messages');
        });
    }
    public function down()  {
        $this->schema->drop('forum_histories');
    }
}
