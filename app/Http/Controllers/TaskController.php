<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // List all tasks for authenticated user
    public function index(Request $request)
    {
        $tasks = Task::where('user_id', Auth::id())
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->has('due_date'), function ($query) use ($request) {
                $query->where('due_date', $request->due_date);
            })
            ->get();

        return response()->json($tasks);
    }

    // Store a new task
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in-progress,completed',
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'due_date' => $validated['due_date'],
            'status' => $validated['status'],
            'user_id' => Auth::id(),
        ]);

        return response()->json($task, 201);
    }

    // Show a specific task
    public function show($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($task);
    }

    // Update a specific task
    public function update(Request $request, $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in-progress,completed',
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    // Delete a specific task
    public function destroy($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
