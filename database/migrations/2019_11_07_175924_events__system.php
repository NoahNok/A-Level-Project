<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventsSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() // Most integers are unsigned as they will always be positive
    {
        Schema::create('event_templates', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->char('name', 10); // e.g Running Race
            $table->char('type', 10); // e.g Distance (Distance, Time, Points, etc)(
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) { //
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('etid')->unsigned(); // etid -> event template id
            $table->char('name', 50); // e.g 400m Sprint
            $table->integer('maxPerFormPerYear')->unsigned()->default(2); // max students per form per year. Defaults to 1 for single events, can be modified for group events
            $table->dateTime('startDate');
            $table->dateTime('endDate');
            $table->char('result', 100); // For quick results viewing e.g Noah Hollowell (13H) 2m 12s or 13H 4 Goals (4:3 H:J)
            $table->timestamps();
        });

        Schema::create('event_users', function (Blueprint $table) { // A link table so requires no primary key but will contain foreign keys
            $table->bigInteger('eid')->unsigned();
            $table->bigInteger('uid')->unsigned();
            $table->boolean('partaking')->default(1);
            $table->timestamps();

            // The following setup the relational constraints
            $table->foreign('eid')->references('id')->on('events')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('event_scores', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned();
            $table->bigInteger('eid')->unsigned();
            $table->bigInteger('uid')->unsigned();
            $table->integer('score'); // A generic integer. Time can be stored in seconds or even milliseconds, distance in centimetres or millimeters, or a score tally
            $table->boolean('teamDupe')->default(0); // If its a team event we dont want to count the score multiple times when finishing the event.
            $table->timestamps();

            // The following setup the relational constraints
            $table->foreign('eid')->references('id')->on('events')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_templates');
        Schema::dropIfExists('events');
        Schema::dropIfExists('event_users');
        Schema::dropIfExists('event_scores');
    }
}
