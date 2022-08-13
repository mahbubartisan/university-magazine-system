<?php

use Illuminate\Database\Seeder;
use App\Faculty;

class FacultyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Faculty::insert([
            'name' => 'CSE',
            'slug' => 'cse',
        ]);
        Faculty::insert([
            'name' => 'EEE',
            'slug' => 'eee',
        ]);
        Faculty::insert([
            'name' => 'BBA',
            'slug' => 'bba',
        ]);
        Faculty::insert([
            'name' => 'IT',
            'slug' => 'it',
        ]);
        Faculty::insert([
            'name' => 'Civil',
            'slug' => 'civil',
        ]);
        Faculty::insert([
            'name' => 'Accounting',
            'slug' => 'accounting',
        ]);
        Faculty::insert([
            'name' => 'Textile',
            'slug' => 'textile',
        ]);
        Faculty::insert([
            'name' => 'English',
            'slug' => 'english',
        ]);
    }
}
