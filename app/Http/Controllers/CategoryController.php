<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('tasks')
                               ->with('tasks')
                               ->where('user_id', auth()->id())
                               ->latest()
                               ->get();

        return view('category.index', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,NULL,id,user_id,' . auth()->id(),
            'icon' => 'required|string',
        ]);

        Category::create([
            'user_id' => auth()->id(),
            'name'    => $request->name,
            'icon'    => $request->icon,
        ]);

        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        // Pastiin kategori milik user yang login
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        return view('category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id . ',id,user_id,' . auth()->id(),
            'icon' => 'required|string',
        ]);

        $category->update([
            'name' => $request->name,
            'icon' => $request->icon,
        ]);

        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy(Category $category)
    {
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        $pendingTasks = $category->tasks()->pending()->count();

        if ($pendingTasks > 0) {
            return redirect()->route('category.index')
                             ->with('error', "Kategori \"{$category->name}\" tidak bisa dihapus karena masih memiliki {$pendingTasks} task yang belum selesai.");
        }

        $totalTasks = $category->tasks()->count();
        $category->delete();

        $message = $totalTasks > 0
            ? "Kategori \"{$category->name}\" berhasil dihapus. {$totalTasks} task tetap tersimpan tanpa kategori."
            : "Kategori \"{$category->name}\" berhasil dihapus.";

        return redirect()->route('category.index')->with('success', $message);
    }
}
