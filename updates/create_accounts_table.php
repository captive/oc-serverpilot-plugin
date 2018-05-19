<?php namespace Awebsome\ServerPilot\Updates;

use DB;
use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Awebsome\ServerPilot\Models\Settings as Conf;

class CreateAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('awebsome_serverpilot_accounts', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('email')->nullable();
            $table->string('client_id');
            $table->string('api_key');
            $table->timestamps();
        });

        if(Conf::get('CLIENT_ID') && Conf::get('API_KEY'))
        {
            /**
             * Migrate main account from settings to Accounts.
             */
            $account = [
                [
                    'client_id' => Conf::get('CLIENT_ID'),
                    'api_key' => Conf::get('API_KEY'),
                ],
            ];

            DB::table('awebsome_serverpilot_accounts')->insert($account);
        }
    }

    public function down()
    {
        Schema::dropIfExists('awebsome_serverpilot_accounts');
    }
}
