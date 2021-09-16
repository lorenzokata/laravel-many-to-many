<?php

use Illuminate\Database\Seeder;
use Illuminate\support\Str;
use App\Post;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 10; $i++) { 

            // creare l'istanza
            $newPost = new Post();

            // generare i dati
            $newPost->title = 'Post n° ' . ($i + 1);
            $newPost->slug = Str::slug($newPost->title, '-');
            $newPost->content = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum praesentium optio voluptatibus officiis velit eum debitis rem similique quasi animi eligendi fugit modi, corrupti quod, sapiente asperiores unde minus laudantium.';
            
            // salvare i dati
            $newPost->save();
        }
    }
}
