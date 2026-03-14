@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">📊 Dashboard</h1>
        <p class="text-gray-400 text-sm mt-1">{{ Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-xs text-gray-400 mb-1">Total Task</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalTasks }}</p>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-xs text-gray-400 mb-1">Selesai</p>
            <p class="text-3xl font-bold text-green-500">{{ $completedTasks }}</p>
            @if ($completedToday > 0)
                <p class="text-xs text-green-400 mt-1">+{{ $completedToday }} hari ini</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-xs text-gray-400 mb-1">Pending</p>
            <p class="text-3xl font-bold text-yellow-500">{{ $pendingTasks }}</p>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-xs text-gray-400 mb-1">Due Hari Ini</p>
            <p class="text-3xl font-bold {{ $dueTodayTasks > 0 ? 'text-red-500' : 'text-gray-300' }}">
                {{ $dueTodayTasks }}
            </p>
        </div>

    </div>

    {{-- Baris Bawah --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Progress per Kategori --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-bold text-gray-700 mb-4">📁 Progress per Kategori</h2>

            @forelse($categories as $cat)
                <div class="mb-4 last:mb-0">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-700">
                            {{ $cat['icon'] }} {{ $cat['name'] }}
                        </span>
                        <span class="text-xs text-gray-400">
                            {{ $cat['completed'] }}/{{ $cat['total'] }} · {{ $cat['progress'] }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all duration-500
                                {{ $cat['progress'] == 100 ? 'bg-green-500' : 'bg-indigo-500' }}"
                            style="width: {{ $cat['progress'] }}%">
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-sm">Belum ada kategori dengan task.</p>
            @endforelse
        </div>

        {{-- Task Jatuh Tempo --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-bold text-gray-700 mb-4">📅 Task Jatuh Tempo</h2>

            {{-- Overdue --}}
            @if ($overdueTasks->count() > 0)
                <p class="text-xs font-semibold text-red-400 uppercase mb-2">Terlambat</p>
                @foreach ($overdueTasks as $task)
                    <a href="{{ route('tasks.show', $task) }}"
                        class="flex items-center justify-between py-2 border-b last:border-0 hover:bg-red-50 rounded px-1 transition">
                        <div class="flex items-center gap-2">
                            <span class="text-red-400">🔴</span>
                            <span class="text-sm text-gray-700">{{ $task->title }}</span>
                        </div>
                        <span class="text-xs text-red-400">
                            {{ $task->due_date->diffForHumans() }}
                        </span>
                    </a>
                @endforeach
                <div class="mb-3"></div>
            @endif

            {{-- Upcoming --}}
            @if ($upcomingTasks->count() > 0)
                <p class="text-xs font-semibold text-gray-400 uppercase mb-2">Upcoming</p>
                @foreach ($upcomingTasks as $task)
                    <a href="{{ route('tasks.show', $task) }}"
                        class="flex items-center justify-between py-2 border-b last:border-0 hover:bg-gray-50 rounded px-1 transition">
                        <div class="flex items-center gap-2">
                            <span>{{ $task->due_date->isToday() ? '⚠️' : '📌' }}</span>
                            <span class="text-sm text-gray-700">{{ $task->title }}</span>
                        </div>
                        <span
                            class="text-xs {{ $task->due_date->isToday() ? 'text-orange-400 font-semibold' : 'text-gray-400' }}">
                            {{ $task->due_date->isToday() ? 'Hari ini!' : $task->due_date->diffForHumans() }}
                        </span>
                    </a>
                @endforeach
            @endif

            @if ($overdueTasks->count() === 0 && $upcomingTasks->count() === 0)
                <p class="text-gray-400 text-sm">Tidak ada task yang jatuh tempo. 🎉</p>
            @endif
        </div>

    </div>

@endsection
