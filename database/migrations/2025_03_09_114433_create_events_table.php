<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_type_id')->references('id')->on('event_types');            // foreign key to event_types table
            $table->foreignId('event_location_id')->references('id')->on('event_locations');    // foreign key to event_locations table
            $table->dateTime('date_start');                                                                  // start date of the event
            $table->dateTime('date_end');                                                                    // end date of the event
            $table->string('uid', 65)->unique();                                                      // unique identifier of the event
            $table->string('desc_de_override', 1500)->nullable();                                    // description of the event in german (override) -> if NULL use default, if '-' use no description, else use the override
            $table->string('desc_en_override', 1500)->nullable();                                    // description of the event in english (override) -> if NULL use default, if '-' use no description, else use the override
            $table->integer('sequence')->default(0);                                                  // sequence number of the event / "version" of the event
            $table->timestamps();
            // explicit date created / updated not necessary, already included in timestamps()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
