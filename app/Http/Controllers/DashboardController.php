<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Statistik Utama
        $totalTasks     = Task::parentOnly()->where('user_id', $userId)->count();
        $completedTasks = Task::parentOnly()->where('user_id', $userId)->completed()->count();
        $pendingTasks   = Task::parentOnly()->where('user_id', $userId)->pending()->count();
        $dueTodayTasks  = Task::parentOnly()->where('user_id', $userId)->pending()
            ->whereDate('due_date', Carbon::today())
            ->count();

        // Progress per Kategori
        $categories = Category::with(['tasks' => function ($q) use ($userId) {
            $q->parentOnly()->where('user_id', $userId);
        }])
            ->where('user_id', $userId) 
            ->get()
            ->map(function ($cat) {
                $total     = $cat->tasks->count();
                $completed = $cat->tasks->where('is_completed', true)->count();
                $progress  = $total > 0 ? round(($completed / $total) * 100) : 0;

                return [
                    'name'      => $cat->name,
                    'icon'      => $cat->icon,
                    'total'     => $total,
                    'completed' => $completed,
                    'progress'  => $progress,
                ];
            })->filter(fn($cat) => $cat['total'] > 0);

        // Task Jatuh Tempo
        $upcomingTasks = Task::parentOnly()->where('user_id', $userId)
            ->pending()
            ->whereNotNull('due_date')
            ->whereDate('due_date', '>=', Carbon::today())
            ->orderBy('due_date')
            ->take(5)
            ->with('category')
            ->get();

        $overdueTasks = Task::parentOnly()->where('user_id', $userId)
            ->pending()
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', Carbon::today())
            ->orderBy('due_date')
            ->with('category')
            ->get();

        // Task selesai hari ini
        $completedToday = Task::parentOnly()->where('user_id', $userId)
            ->completed()
            ->whereDate('completed_at', Carbon::today())
            ->count();

        return view('dashboard', compact(
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'dueTodayTasks',
            'categories',
            'upcomingTasks',
            'overdueTasks',
            'completedToday'
        ));
    }
}
