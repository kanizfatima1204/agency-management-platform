<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return Inertia::render('Dashboard/Admin', [
                'stats' => [
                    'clients' => User::where('role', 'client')->count(),
                    'team' => User::whereIn('role', ['team', 'intern'])->count(),
                    'projects' => Project::count(),
                    'revenue' => Payment::where('status', 'paid')->sum('amount'),
                    'pending' => Task::where('status', '!=', 'done')->count(),
                ],
                'projects' => Project::with('client')->latest()->take(8)->get(),
            ]);
        }

        if ($user->role === 'client') {
            $projects = Project::where('client_id', $user->id)->withCount('tasks')->latest()->get();

            return Inertia::render('Dashboard/Client', [
                'projects' => $projects,
                'stats' => [
                    'projects' => $projects->count(),
                    'active' => $projects->where('status', 'active')->count(),
                    'completed' => $projects->where('status', 'completed')->count(),
                ],
            ]);
        }

        $projects = $user->projects()->with('client')->latest()->get();
        $tasks = Task::where('assignee_id', $user->id)->with('project')->latest()->get();
        $stats = [
            'projects' => $projects->count(),
            'open' => $tasks->where('status', '!=', 'done')->count(),
            'completed' => $tasks->where('status', 'done')->count(),
        ];

        if ($user->role === 'intern') {
            return Inertia::render('Dashboard/Intern', compact('projects', 'tasks', 'stats'));
        }

        return Inertia::render('Dashboard/Team', compact('projects', 'tasks', 'stats'));
    }
}
