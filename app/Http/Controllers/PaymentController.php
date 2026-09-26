<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $payments = Payment::with('project.client')->latest();

        if ($user->role === 'client') {
            $payments->whereHas('project', fn ($query) => $query->where('client_id', $user->id));
        } elseif (! $user->isAdmin()) {
            $payments->whereHas('project.members', fn ($query) => $query->where('users.id', $user->id));
        }

        $rows = $payments->get();

        return Inertia::render('Payments/Index', [
            'payments' => $rows,
            'stats' => [
                'paid' => $rows->where('status', 'paid')->count(),
                'pending' => $rows->where('status', 'pending')->count(),
                'overdue' => $rows->where('status', 'overdue')->count(),
            ],
            'isAdmin' => $user->isAdmin(),
        ]);
    }

    public function store(Request $request, Project $project)
    {
        abort_unless($request->user()->isAdmin(), 403);
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01', 'currency' => 'required|alpha|size:3',
            'status' => 'required|in:pending,paid,overdue,refunded', 'method' => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:100', 'due_date' => 'nullable|date',
        ]);
        if ($data['status'] === 'paid') $data['paid_at'] = now();
        $project->payments()->create($data);

        return back()->with('success', 'Payment recorded.');
    }
}
