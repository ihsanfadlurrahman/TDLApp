<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with('category')->latest();

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->completed();
            } elseif ($request->status === 'pending') {
                $query->pending();
            }
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->priority($request->priority);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $tasks      = $query->paginate(10, ['*'], 'page', request()->query('page'));
        $tasks      = $tasks->appends(request()->query());
        $categories = Category::all();

        return view('tasks.index', compact('tasks', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'due_date'    => 'nullable|date|after_or_equal:today',
            'priority'    => 'required|in:low,medium,high',
        ]);

        Task::create($request->only(
            'title', 'description', 'category_id', 'due_date', 'priority'
        ));

        return redirect()->route('tasks.index')
                         ->with('success', 'Task berhasil ditambahkan!');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $categories = Category::all();
        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'due_date'    => 'nullable|date',
            'priority'    => 'required|in:low,medium,high',
            'is_completed'=> 'boolean',
        ]);

        $task->update($request->only(
            'title', 'description', 'category_id', 'due_date', 'priority', 'is_completed'
        ));

        return redirect()->route('tasks.index')
                         ->with('success', 'Task berhasil diupdate!');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
                         ->with('success', 'Task berhasil dihapus!');
    }

    // Toggle complete/incomplete langsung dari index
    public function toggle(Task $task)
    {
        $task->update(['is_completed' => !$task->is_completed]);

        return back()->with('success', 'Status task berhasil diubah!');
    }
}
