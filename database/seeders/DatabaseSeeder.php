<?php

namespace Database\Seeders;

use App\Models\Company; 
use App\Models\Contact;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Factory::factoryForModel(\App\Models\Company::class)->count(10)->create()->each(function ($company){
            $company->contacts()->saveMany(
                
                Factory::factoryForModel(\App\Models\Contact::class)->count(rand(5,10))->make()
            );
        });
       
    }
}
