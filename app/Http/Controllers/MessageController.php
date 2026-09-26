<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $messages = Message::with(['project', 'user'])->latest();

        if (! $user->isAdmin()) {
            $messages->whereHas('project', function ($query) use ($user) {
                if ($user->role === 'client') {
                    $query->where('client_id', $user->id);
                } else {
                    $query->whereHas('members', fn ($members) => $members->where('users.id', $user->id));
                }
            });
        }

        $projects = Project::query()->select(['id', 'name']);
        if ($user->role === 'client') {
            $projects->where('client_id', $user->id);
        } elseif (! $user->isAdmin()) {
            $projects->whereHas('members', fn ($members) => $members->where('users.id', $user->id));
        }

        return Inertia::render('Messages/Index', [
            'messages' => $messages->take(100)->get(),
            'projects' => $projects->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, Project $project)
    {
        $user = $request->user();
        abort_unless($user->isAdmin() || $project->client_id === $user->id || $project->members()->where('users.id', $user->id)->exists(), 403);
        $data = $request->validate(['body' => 'required|string|max:5000']);
        $project->messages()->create(['user_id' => $user->id, 'body' => $data['body']]);

        return back()->with('success', 'Message sent.');
    }
}
