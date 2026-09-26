<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $tasks = Task::with(['project.client', 'assignee'])->latest();

        if ($user->role === 'client') {
            $tasks->whereHas('project', fn ($query) => $query->where('client_id', $user->id));
        } elseif (! $user->isAdmin()) {
            $tasks->where('assignee_id', $user->id);
        }

        return Inertia::render('Tasks/Index', ['tasks' => $tasks->paginate(30)]);
    }

    public function store(Request $request, Project $project)
    {
        $user = $request->user();
        abort_unless($user->isAdmin() || $project->members()->where('users.id', $user->id)->exists(), 403);
        $data = $request->validate([
            'title' => 'required|string|max:150', 'assignee_id' => 'required|exists:users,id',
            'status' => 'required|in:todo,in_progress,review,done', 'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
        ]);
        abort_unless(User::whereKey($data['assignee_id'])->whereIn('role', ['team', 'intern'])->exists(), 422);
        abort_unless($user->isAdmin() || $project->members()->where('users.id', $data['assignee_id'])->exists(), 422);
        $project->tasks()->create($data + ['created_by' => $user->id]);
        $this->syncProgress($project);

        return back()->with('success', 'Task created.');
    }

    public function update(Request $request, Task $task)
    {
        abort_unless($request->user()->isAdmin() || $task->assignee_id === $request->user()->id, 403);
        $task->update($request->validate([
            'title' => 'required|string|max:150', 'status' => 'required|in:todo,in_progress,review,done',
            'priority' => 'required|in:low,medium,high,urgent', 'due_date' => 'nullable|date',
        ]));
        $this->syncProgress($task->project);

        return back()->with('success', 'Task updated.');
    }

    private function syncProgress(Project $project): void
    {
        $total = $project->tasks()->count();
        $project->update(['progress' => $total ? (int) round($project->tasks()->where('status', 'done')->count() / $total * 100) : 0]);
    }
}
