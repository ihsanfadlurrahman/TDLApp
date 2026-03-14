<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['category', 'subTasks'])
            ->parentOnly() // hanya tampilkan parent task
            ->where('user_id', auth()->id())
            ->latest();

        if ($request->filled('status')) {
            if ($request->status === 'completed') $query->completed();
            elseif ($request->status === 'pending') $query->pending();
        }

        if ($request->filled('priority')) {
            $query->priority($request->priority);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $tasks      = $query->paginate(10)->withQueryString();
        $categories = Category::where('user_id', auth()->id())->get(); // Filter kategori milik user yang sedang login

        return view('tasks.index', compact('tasks', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('user_id', auth()->id())->get(); // Filter kategori milik user yang sedang login
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

        Task::create([
            'user_id'      => auth()->id(),
            'title'        => $request->title,
            'description'  => $request->description,
            'category_id'  => $request->category_id,
            'due_date'     => $request->due_date,
            'priority'     => $request->priority,
            'is_completed' => false,
        ]);

        return redirect()->route('tasks.index')
            ->with('success', 'Task berhasil ditambahkan!');
    }

    public function show(Task $task)
    {
        // Memastikan yang ditampilkan adalah parent task, jika sub-task diarahkan ke parent-nya
        if ($task->isSubTask()) {
            return redirect()->route('tasks.show', $task->parent_id);
        }

        $task->load('subTasks', 'category');
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $categories = Category::where('user_id', auth()->id())->get(); // Filter kategori milik user yang sedang login
        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'category_id'  => 'nullable|exists:categories,id',
            'due_date'     => 'nullable|date',
            'priority'     => 'required|in:low,medium,high',
            'is_completed' => 'boolean',
        ]);

        $task->update($request->only(
            'title',
            'description',
            'category_id',
            'due_date',
            'priority',
            'is_completed'
        ));

        return redirect()->route('tasks.index')
            ->with('success', 'Task berhasil diupdate!');
    }

    public function destroy(Task $task)
    {
        // Hapus semua sub-task, sebelum hapus parent
        $task->subTasks()->delete();
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task berhasil dihapus!');
    }

    // Toggle complete/incomplete
    public function toggle(Task $task)
    {
        if (!$task->isSubTask()) {
            $newStatus = !$task->is_completed;
            $task->subTasks()->update([
                'is_completed' => $newStatus,
                'completed_at' => $newStatus ? now() : null,
            ]);
            $task->update([
                'is_completed' => $newStatus,
                'completed_at' => $newStatus ? now() : null,
            ]);
        } else {
            $task->update([
                'is_completed' => !$task->is_completed,
                'completed_at' => !$task->is_completed ? now() : null,
            ]);
            $task->checkAndCompleteParent();
        }

        return back()->with('success', 'Status task berhasil diubah!');
    }
    // ── Sub-task ──────────────────────────────────────────

    public function storeSubTask(Request $request, Task $task)
    {
        // Memastikan hanya parent task yang bisa ditambahkan sub-task
        if ($task->isSubTask()) {
            return back()->with('error', 'Sub-task tidak bisa punya sub-task lagi.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Task::create([
            'parent_id'    => $task->id,
            'title'        => $request->title,
            'is_completed' => false,
            'priority'     => 'medium', // default
        ]);

        return back()->with('success', 'Sub-task berhasil ditambahkan!');
    }

    public function destroySubTask(Task $subTask)
    {
        $parentId = $subTask->parent_id;
        $subTask->delete();

        // Cek ulang status parent setelah sub-task dihapus
        $parent = Task::find($parentId);
        if ($parent) {
            $allDone = $parent->subTasks()->pending()->count() === 0
                && $parent->subTasks()->count() > 0;
            $parent->update(['is_completed' => $allDone]);
        }

        return back()->with('success', 'Sub-task berhasil dihapus!');
    }
}
