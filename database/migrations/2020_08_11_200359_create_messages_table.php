<?php

use App\Message;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Message::TABLE, function (Blueprint $table) {
            $table->id();
            $table->integer(Message::FROM)->unsigned();
            $table->integer(Message::TO)->unsigned();
            $table->text(Message::TEXT);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Message::TABLE);
    }
}
