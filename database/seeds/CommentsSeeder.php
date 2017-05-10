<?php

use Illuminate\Database\Seeder;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comments')->delete();
          $data = [
            ["id" => 1 ,"reply" => "Oh Edward wasnt shining under the sun in Brazil they forgot that part and thats a huge mistake in vampire movie if he does not afraid of the sun light. ","post_id" => 1],
            ["id" => 2 ,"reply" => "They could do better then this but before change the actress she is horrible in acting. ","post_id"=>1],
            ["id" => 3 ,"reply" => "The Twilight Saga: Breaking Dawn  Part 1 is a horrible film and the worst one from the saga so far. To start with, this first episode of the fourth part introducesonly to leave them aside and waste their potential  and the story feels terribly boring","post_id"=>1],
           ["id" => 4 ,"reply" => "In conclusion, The Twilight Saga: Breaking Dawn - Part 1 is an unbearable film, which made me snooze very frequently and made me feel like wasting my time. I know that this film is having many female fans...I respect them, but I honestly do not understand them", "post_id"=>1],

           ["id" => 5 ,"reply" => "In conclusion, The Twilight Saga: Breaking Dawn - Part 1 is an unbearable film, which made me snooze very frequently and made me feel like wasting my time. I know that this film is having many female fans...I respect them, but I honestly do not understand them.","post_id"=>2],

            ["id" => 6 ,"reply" => "I have to mention that The Twilight Saga: Breaking Dawn - Part 1 offers some moments of involuntary humor. From the pathetic performances brought by Stewart and Pattinson to the weak special effects, there are some opportunities to laugh at the technical, histrionic and narrative blunders from this film. And since I just mentioned the special effects, the werewolves look totally fake, and they appear in one of the worst scenes I have seen in a long time...one of those which provoke an absolute embarrassment combined with the incredulity of knowing that someone considered that a good idea.","post_id"=>2],
            ["id" => 7 ,"reply" => "In conclusion, The Twilight Saga: Breaking Dawn - Part 1 is an unbearable film, which made me snooze very frequently and made me feel like wasting my time. I know that this film is having many female fans...I respect them, but I honestly do not understand them.","post_id"=>2]
           
           
        ];

        DB::table('comments')->insert($data);
    }
}
