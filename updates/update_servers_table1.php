<?php namespace Awebsome\ServerPilot\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateServersTable1 extends Migration
{
    public function up()
    {            
        Schema::table('awebsome_serverpilot_servers', function($table)
        {
            $table->json('available_runtimes');
        });
    }

    public function down()
    {
        Schema::table('awebsome_serverpilot_servers', function($table)
        {
            $table->dropColumn('available_runtimes');
        });
    }
}
