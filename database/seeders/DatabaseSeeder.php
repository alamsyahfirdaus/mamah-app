<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(UserSeeder::class);
        $this->call(ModuleCategorySeeder::class);
        $this->call(EducationalModuleSeeder::class);
        $this->call(ScreeningQuestionSeeder::class);
        $this->call(ScreeningChoiceSeeder::class);
        $this->call(ScreeningResultSeeder::class);
        $this->call(SupportGroupSeeder::class);
        $this->call(GroupMessageSeeder::class);
        $this->call(ConsultationSeeder::class);
        $this->call(ConsultationReplySeeder::class);
        $this->call(ScreeningLevelsSeeder::class);
    }
}
