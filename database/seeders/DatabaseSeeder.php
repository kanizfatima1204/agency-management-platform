<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Keep these demo accounts available for the public MVP. firstOrCreate
        // prevents a container restart from resetting an existing user's password.
        $admin = User::firstOrCreate(['email' => 'admin@agency.test'], [
            'name' => 'Agency Admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $client = User::firstOrCreate(['email' => 'client@agency.test'], [
            'name' => 'Acme Client',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);
        $team = User::firstOrCreate(['email' => 'team@agency.test'], [
            'name' => 'Team Member',
            'password' => Hash::make('password'),
            'role' => 'team',
        ]);
        $intern = User::firstOrCreate(['email' => 'intern@agency.test'], [
            'name' => 'Agency Intern',
            'password' => Hash::make('password'),
            'role' => 'intern',
        ]);

        $project = Project::firstOrCreate(['slug' => 'acme-growth-website'], [
            'client_id' => $client->id,
            'name' => 'Acme Growth Website',
            'description' => 'Conversion-focused agency project.',
            'status' => 'active',
            'priority' => 'high',
            'budget' => 4800,
            'due_date' => now()->addDays(25),
            'progress' => 50,
        ]);

        $project->members()->syncWithoutDetaching([$team->id, $intern->id]);

        Task::firstOrCreate(
            ['project_id' => $project->id, 'title' => 'Build responsive hero'],
            ['assignee_id' => $team->id, 'created_by' => $admin->id, 'status' => 'in_progress', 'priority' => 'high', 'due_date' => now()->addDays(3)],
        );
        Task::firstOrCreate(
            ['project_id' => $project->id, 'title' => 'Run QA checklist'],
            ['assignee_id' => $intern->id, 'created_by' => $admin->id, 'status' => 'todo', 'priority' => 'medium', 'due_date' => now()->addDays(7)],
        );
        Payment::firstOrCreate(
            ['project_id' => $project->id, 'reference' => 'ACME-001'],
            ['amount' => 2400, 'currency' => 'USD', 'status' => 'paid', 'method' => 'Bank Transfer', 'paid_at' => now()],
        );
    }
}
