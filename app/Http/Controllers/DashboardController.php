<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        Task::parentOnly()->where('user_id', auth()->id());
        //  Statistik Utama
        $totalTasks     = Task::parentOnly()->count();
        $completedTasks = Task::parentOnly()->completed()->count();
        $pendingTasks   = Task::parentOnly()->pending()->count();
        $dueTodayTasks  = Task::parentOnly()->pending()
                              ->whereDate('due_date', Carbon::today())
                              ->count();

        //  Progress per Kategori
        $categories = Category::with(['tasks' => function ($q) {
                            $q->parentOnly()->where('user_id', auth()->id());
                        }])->get()->map(function ($cat) {
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
                        })->filter(fn($cat) => $cat['total'] > 0); // Hanya menampilkan kategori yang memiliki task

        //  Task yang Jatuh Tempo
        $upcomingTasks = Task::parentOnly()
                             ->pending()
                             ->whereNotNull('due_date')
                             ->whereDate('due_date', '>=', Carbon::today())
                             ->orderBy('due_date')
                             ->take(5)
                             ->with('category')
                             ->get();

        $overdueTasks = Task::parentOnly()
                            ->pending()
                            ->whereNotNull('due_date')
                            ->whereDate('due_date', '<', Carbon::today())
                            ->orderBy('due_date')
                            ->with('category')
                            ->get();

        //  Task yang selesai hari ini
        $completedToday = Task::parentOnly()
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
