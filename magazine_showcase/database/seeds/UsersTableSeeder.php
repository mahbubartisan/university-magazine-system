<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'name' => 'Admin',
            'gander' => 'male',
            'email' => 'admin@gmail.com',
            'user_type' => 'Administrator',
            'status' => 'enabled',
            'password' => Hash::make('111111'),
        ]);

        User::insert([
            'name' => 'Marketing Manager',
            'gander' => 'male',
            'email' => 'm_manager@gmail.com',
            'user_type' => 'Marketing Manager',
            'status' => 'enabled',
            'password' => Hash::make('111111'),
        ]);

        User::insert([
            'name' => 'Marketing Coordinator 1',
            'gander' => 'male',
            'email' => 'm_coordinator1@gmail.com',
            'user_type' => 'Marketing Coordinator',
            'status' => 'enabled',
            'password' => Hash::make('111111'),
        ]);

        // custom codes
        $user_id = User::where('email','=', 'm_coordinator1@gmail.com')->value('id');
        $faculty_id = '1';

        $user = User::find($user_id);
        $user->user_faculty()->attach($faculty_id);

        User::insert([
            'name' => 'Marketing Coordinator 2',
            'gander' => 'male',
            'email' => 'm_coordinator2@gmail.com',
            'user_type' => 'Marketing Coordinator',
            'status' => 'enabled',
            'password' => Hash::make('111111'),
        ]);

        // custom codes
        $user_id = User::where('email','=', 'm_coordinator2@gmail.com')->value('id');
        $faculty_id = '2';

        $user = User::find($user_id);
        $user->user_faculty()->attach($faculty_id);

        User::insert([
            'name' => 'Guest 1',
            'gander' => 'male',
            'email' => 'guest1@gmail.com',
            'user_type' => 'Guest',
            'status' => 'enabled',
            'password' => Hash::make('111111'),
        ]);

        // custom codes
        $user_id = User::where('email','=', 'guest1@gmail.com')->value('id');
        $faculty_id = '1';

        $user = User::find($user_id);
        $user->user_faculty()->attach($faculty_id);

        User::insert([
            'name' => 'Guest 2',
            'gander' => 'male',
            'email' => 'guest2@gmail.com',
            'user_type' => 'Guest',
            'status' => 'enabled',
            'password' => Hash::make('111111'),
        ]);

        // custom codes
        $user_id = User::where('email','=', 'guest2@gmail.com')->value('id');
        $faculty_id = '2';

        $user = User::find($user_id);
        $user->user_faculty()->attach($faculty_id);

        User::insert([
            'name' => 'Stud 1',
            'gander' => 'male',
            'email' => 'stud1@gmail.com',
            'user_type' => 'Student',
            'status' => 'enabled',
            'password' => Hash::make('111111'),
        ]);

        // custom codes
        $user_id = User::where('email','=', 'stud1@gmail.com')->value('id');
        $faculty_id = '1';

        $user = User::find($user_id);
        $user->user_faculty()->attach($faculty_id);

        User::insert([
            'name' => 'Stud 2',
            'gander' => 'male',
            'email' => 'stud2@gmail.com',
            'user_type' => 'Student',
            'status' => 'enabled',
            'password' => Hash::make('111111'),
        ]);

        // custom codes
        $user_id = User::where('email','=', 'stud2@gmail.com')->value('id');
        $faculty_id = '2';

        $user = User::find($user_id);
        $user->user_faculty()->attach($faculty_id);

        User::insert([
            'name' => 'Stud 3',
            'gander' => 'male',
            'email' => 'stud3@gmail.com',
            'user_type' => 'Student',
            'status' => 'enabled',
            'password' => Hash::make('111111'),
        ]);

        // custom codes
        $user_id = User::where('email','=', 'stud3@gmail.com')->value('id');
        $faculty_id = '1';

        $user = User::find($user_id);
        $user->user_faculty()->attach($faculty_id);

        User::insert([
            'name' => 'Stud 4',
            'gander' => 'male',
            'email' => 'stud4@gmail.com',
            'user_type' => 'Student',
            'status' => 'enabled',
            'password' => Hash::make('111111'),
        ]);

        // custom codes
        $user_id = User::where('email','=', 'stud4@gmail.com')->value('id');
        $faculty_id = '1';

        $user = User::find($user_id);
        $user->user_faculty()->attach($faculty_id);

        User::insert([
            'name' => 'Stud 5',
            'gander' => 'male',
            'email' => 'stud5@gmail.com',
            'user_type' => 'Student',
            'status' => 'enabled',
            'password' => Hash::make('111111'),
        ]);

        // custom codes
        $user_id = User::where('email','=', 'stud5@gmail.com')->value('id');
        $faculty_id = '3';

        $user = User::find($user_id);
        $user->user_faculty()->attach($faculty_id);

    }
}
