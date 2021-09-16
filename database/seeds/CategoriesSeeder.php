<?php

use Illuminate\Database\Seeder;
use Illuminate\support\Str;
use App\Category;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // creazione array
        $categories = ['Php', 'Node.js', 'Phyton', 'Java', 'Ruby', 'C#'];

        foreach ($categories as $category) {
            
            $newCategory = new Category();
            $newCategory->name = $category;
            $newCategory->slug = Str::slug($category,'-');
            $newCategory->save();
        }

    }
}

// CATEGORIES SEEDER