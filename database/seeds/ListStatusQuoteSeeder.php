<?php

use Illuminate\Database\Seeder;

class ListStatusQuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('list_status_quotes')->insert([
          [
            'id' => '1'
            'title' => 'Pending'
          ],
          [
            'id' => '2'
            'title' => 'Sent'
          ],
          [
            'id' => '3'
            'title' => 'Approve'
          ],
          [
            'id' => '4'
            'title' => 'Refuse'
          ],
      ]);
    }
}
