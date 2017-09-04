<?php


use App\User;
use Illuminate\Database\Seeder;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email'=>'admin@admin.com',
            'password'=>Hash::make('1qaz'),
            'nickname'=>'admin',
            'is_admin'=>1,
        ]);
    }
}
