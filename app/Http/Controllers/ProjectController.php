<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $projects = Project::with('client')->latest();

        if ($user->role === 'client') {
            $projects->where('client_id', $user->id);
        } elseif (in_array($user->role, ['team', 'intern'], true)) {
            $projects->whereIn('id', $user->projects()->select('projects.id'));
        }

        return Inertia::render('Projects/Index', [
            'projects' => $projects->paginate(10),
            'clients' => $user->isAdmin() ? User::where('role', 'client')->get(['id', 'name', 'email']) : [],
            'members' => $user->isAdmin() ? User::whereIn('role', ['team', 'intern'])->get(['id', 'name', 'role']) : [],
        ]);
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403);
        $data = $request->validate([
            'client_id' => 'required|exists:users,id', 'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:10000', 'status' => 'required|in:planning,active,on_hold,completed',
            'priority' => 'required|in:low,medium,high,urgent', 'budget' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date', 'members' => 'sometimes|array',
            'members.*' => 'integer|distinct|exists:users,id',
        ]);
        abort_unless(User::whereKey($data['client_id'])->where('role', 'client')->exists(), 422);
        $memberIds = $data['members'] ?? [];
        abort_if($memberIds && User::whereIn('id', $memberIds)->whereNotIn('role', ['team', 'intern'])->exists(), 422);
        unset($data['members']);

        $project = Project::create($data + [
            'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(5)), 'progress' => 0,
        ]);
        $project->members()->sync($memberIds);

        return back()->with('success', 'Project created.');
    }

    public function show(Request $request, Project $project)
    {
        $user = $request->user();
        abort_unless($user->isAdmin() || $project->client_id === $user->id || $project->members()->where('users.id', $user->id)->exists(), 403);

        return Inertia::render('Projects/Show', [
            'project' => $project->load(['client', 'members', 'tasks.assignee', 'payments', 'messages.user', 'files.user']),
            'team' => User::whereIn('role', ['team', 'intern'])->get(['id', 'name', 'role']),
        ]);
    }
}
