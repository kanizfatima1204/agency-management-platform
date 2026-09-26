<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProjectFileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $files = ProjectFile::with(['project.client', 'user'])->latest();

        if ($user->role === 'client') {
            $files->whereHas('project', fn ($query) => $query->where('client_id', $user->id));
        } elseif (! $user->isAdmin()) {
            $files->whereHas('project.members', fn ($query) => $query->where('users.id', $user->id));
        }

        return Inertia::render('Files/Index', ['files' => $files->paginate(30)]);
    }

    public function store(Request $request, Project $project)
    {
        $this->authorizeProject($request, $project);
        $request->validate(['file' => 'required|file|max:10240|mimes:pdf,png,jpg,jpeg,webp,doc,docx,xls,xlsx,txt,csv,zip']);
        $file = $request->file('file');
        $path = $file->store('project-files/'.$project->id, 'local');
        $project->files()->create([
            'user_id' => $request->user()->id,
            'original_name' => mb_substr($file->getClientOriginalName(), 0, 255),
            'path' => $path,
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'size' => $file->getSize(),
        ]);

        return back()->with('success', 'File uploaded.');
    }

    public function download(Request $request, Project $project, ProjectFile $file)
    {
        $this->authorizeProject($request, $project);
        abort_unless($file->project_id === $project->id && Storage::disk('local')->exists($file->path), 404);

        return Storage::disk('local')->download($file->path, $file->original_name);
    }

    private function authorizeProject(Request $request, Project $project): void
    {
        $user = $request->user();
        abort_unless($user->isAdmin() || $project->client_id === $user->id || $project->members()->where('users.id', $user->id)->exists(), 403);
    }
}
