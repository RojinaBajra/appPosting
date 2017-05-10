<?php

use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('posts')->delete();
          $data = [
            ["id" => 1 ,"topic" => "Twilight Saga","description" => "The Twilight Saga is a series of five romance fantasy films from Summit Entertainment based on the four novels by American author Stephenie Meyer. "],
             ["id" => 2 ,"topic" => "Breaking Dawn","description" => "The Breaking dawn is a series of five romance fantasy films from Summit Entertainment based on the four novels by American author Stephenie Meyer. "]
           
           
           
        ];

        DB::table('posts')->insert($data);
    }
}
