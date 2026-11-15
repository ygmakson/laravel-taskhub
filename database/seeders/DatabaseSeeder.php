<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tags = Tag::factory(8)->create(); // Создали 8 рандомных тегов
        $users = User::factory(10)->create(); // Создали 10 рандомных юзеров

        foreach ($users as $owner) { // пробегаемся по всем юзерам, и каждого берем как owner
            $projects = Project::factory() // Вызов фабрики, для создания тестовых проектов
                ->count(rand(2, 3)) // рандомное количество проектов, от 2 до 3
                ->create(['owner_id' => $owner->id]); // Создаем либо 2 либо 3 проекта, при этом обязательно
            // ставим owner_id у них, id юзера, в итоге у каждого пользователя есть от 2 до 3 проектов где он owner

            foreach ($projects as $project) { // перебираем все созданные проекты, для дальнейшего добавления участников
                //, задач и тегов для задач

                // 1. владелец как участник
                $project->users()->attach($owner->id, ['role' => 'owner']);

                // 2. случайные участники (кроме владельца)
                $members = $users->where('id', '!=', $owner->id)->random(rand(2, 3));
                $project->users()->attach($members->pluck('id'), ['role' => 'member']);

                // 3. задачи
                $participants = $project->users;

                for ($i = 0; $i < rand(5, 10); $i++) {
                    $task = Task::factory()->create([
                        'project_id' => $project->id,
                        'assignee_id' => $participants->random()->id,
                    ]);

                    // 4. теги
                    $task->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
                }
            }
        }

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
    }
}
