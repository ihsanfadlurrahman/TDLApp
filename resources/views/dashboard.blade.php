@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white" style="font-family:'Syne',sans-serif;">Dashboard</h1>
        <p class="text-white/40 text-sm mt-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="navy-card rounded-2xl p-5" style="border-top: 2px solid #2563eb;">
            <p class="text-xs text-white/30 mb-1 uppercase tracking-widest">Total Task</p>
            <p class="text-3xl font-bold text-white">{{ $totalTasks }}</p>
            <p class="text-xs text-white/30 mt-1">{{ $categories->count() }} kategori aktif</p>
        </div>
        <div class="navy-card rounded-2xl p-5" style="border-top: 2px solid #10b981;">
            <p class="text-xs text-white/30 mb-1 uppercase tracking-widest">Selesai</p>
            <p class="text-3xl font-bold text-emerald-400">{{ $completedTasks }}</p>
            @if ($completedToday > 0)
                <p class="text-xs text-emerald-500/70 mt-1">+{{ $completedToday }} hari ini</p>
            @else
                <p class="text-xs text-white/30 mt-1">dari total task</p>
            @endif
        </div>
        <div class="navy-card rounded-2xl p-5" style="border-top: 2px solid #f59e0b;">
            <p class="text-xs text-white/30 mb-1 uppercase tracking-widest">Pending</p>
            <p class="text-3xl font-bold text-amber-400">{{ $pendingTasks }}</p>
            <p class="text-xs text-white/30 mt-1">masih berjalan</p>
        </div>
        <div class="navy-card rounded-2xl p-5"
            style="border-top: 2px solid {{ $dueTodayTasks > 0 ? '#ef4444' : '#1a2d5a' }};">
            <p class="text-xs text-white/30 mb-1 uppercase tracking-widest">Due Hari Ini</p>
            <p class="text-3xl font-bold {{ $dueTodayTasks > 0 ? 'text-red-400' : 'text-white/20' }}">{{ $dueTodayTasks }}
            </p>
            <p class="text-xs {{ $dueTodayTasks > 0 ? 'text-red-500/70' : 'text-white/30' }} mt-1">
                {{ $dueTodayTasks > 0 ? 'segera selesaikan!' : 'semua aman' }}
            </p>
        </div>
    </div>

    {{-- Focus Task --}}
    <div class="mb-6">
        @if ($focusTask)
            @php
                $daysLeft = $focusTask->due_date
                    ? Carbon\Carbon::today()->diffInDays($focusTask->due_date, false)
                    : null;
                $isOverdue = $daysLeft !== null && $daysLeft < 0;
                $isDueToday = $daysLeft === 0;
                $isDueSoon = $daysLeft !== null && $daysLeft > 0 && $daysLeft <= 3;
            @endphp

            <div class="relative overflow-hidden rounded-2xl p-6 border"
                style="background: linear-gradient(135deg, rgba(37,99,235,0.15) 0%, rgba(29,78,216,0.08) 100%);
                    border-color: rgba(37,99,235,0.3);">

                {{-- Background decoration --}}
                <div class="absolute top-0 right-0 w-48 h-48 opacity-5"
                    style="background: radial-gradient(circle, #60a5fa, transparent); transform: translate(30%, -30%);">
                </div>

                {{-- Header --}}
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-lg">🎯</span>
                    <span class="text-xs font-semibold text-blue-300 uppercase tracking-widest">Focus Sekarang</span>
                    @if ($focusTask->priority === 'high')
                        <span class="ml-auto text-xs px-2 py-0.5 rounded-lg font-semibold"
                            style="background:rgba(239,68,68,0.15); color:#f87171; border:1px solid rgba(239,68,68,0.2);">
                            🔴 High Priority
                        </span>
                    @endif
                </div>

                {{-- Task Title --}}
                <a href="{{ route('tasks.show', $focusTask) }}"
                    class="block text-xl font-bold text-white hover:text-blue-300 transition mb-2"
                    style="font-family:'Syne',sans-serif;">
                    {{ $focusTask->title }}
                </a>

                {{-- Meta --}}
                <div class="flex flex-wrap gap-2 mb-4">
                    @if ($focusTask->category)
                        <span class="pill-blue text-xs px-2.5 py-0.5 rounded-lg">
                            {{ $focusTask->category->icon }} {{ $focusTask->category->name }}
                        </span>
                    @endif

                    @if ($focusTask->due_date)
                        <span class="text-xs px-2.5 py-0.5 rounded-lg font-medium"
                            style="{{ $isOverdue
                                ? 'background:rgba(239,68,68,0.15);color:#f87171;border:1px solid rgba(239,68,68,0.2);'
                                : ($isDueToday
                                    ? 'background:rgba(245,158,11,0.15);color:#fbbf24;border:1px solid rgba(245,158,11,0.2);'
                                    : ($isDueSoon
                                        ? 'background:rgba(245,158,11,0.1);color:#fcd34d;border:1px solid rgba(245,158,11,0.15);'
                                        : 'background:rgba(37,99,235,0.12);color:#93c5fd;border:1px solid rgba(37,99,235,0.2);')) }}">
                            📅
                            @if ($isOverdue)
                                Terlambat {{ abs($daysLeft) }} hari
                            @elseif($isDueToday)
                                Due hari ini!
                            @elseif($isDueSoon)
                                {{ $daysLeft }} hari lagi
                            @else
                                {{ $focusTask->due_date->format('d M Y') }}
                            @endif
                        </span>
                    @endif
                </div>

                {{-- Progress (kalau ada subtask) --}}
                @if ($focusTask->subTasks->count() > 0)
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-white/40 mb-1.5">
                            <span>Progress</span>
                            <span>{{ $focusTask->subTasks->where('is_completed', true)->count() }}/{{ $focusTask->subTasks->count() }}
                                sub-task</span>
                        </div>
                        <div class="w-full bg-white/5 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-700 progress-blue"
                                style="width: {{ $focusTask->progress }}%">
                            </div>
                        </div>
                        <p class="text-xs text-white/30 mt-1">{{ $focusTask->progress }}% selesai</p>
                    </div>
                @endif

                {{-- CTA --}}
                <a href="{{ route('tasks.show', $focusTask) }}"
                    class="inline-flex items-center gap-2 btn-primary px-4 py-2 rounded-xl text-sm font-semibold text-white">
                    Kerjain sekarang →
                </a>
            </div>
        @else
            {{-- Pesan motivasi kalau ga ada task yang memenuhi syarat --}}
            <div class="navy-card rounded-2xl p-6 flex items-center gap-4">
                <div class="text-4xl">🎉</div>
                <div>
                    <p class="font-semibold text-white/80 text-sm">Semua task under control!</p>
                    <p class="text-white/40 text-xs mt-0.5">Tidak ada task mendesak hari ini. Kerja bagus, tetap semangat!
                    </p>
                </div>
            </div>
        @endif
    </div>

    {{-- Bottom Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="navy-card rounded-2xl p-6">
            <h2 class="text-sm font-semibold text-white/80 mb-5 uppercase tracking-widest">Progress per Kategori</h2>
            @forelse($categories as $cat)
                <div class="mb-5 last:mb-0">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-white/60">{{ $cat['icon'] }} {{ $cat['name'] }}</span>
                        <span class="text-xs text-white/30">{{ $cat['completed'] }}/{{ $cat['total'] }} ·
                            {{ $cat['progress'] }}%</span>
                    </div>
                    <div class="w-full bg-white/5 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full transition-all duration-700
                                {{ $cat['progress'] == 100 ? 'progress-green' : 'progress-blue' }}"
                            style="width: {{ $cat['progress'] }}%">
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-white/30">Belum ada kategori dengan task.</p>
            @endforelse
        </div>

        <div class="navy-card rounded-2xl p-6">
            <h2 class="text-sm font-semibold text-white/80 mb-5 uppercase tracking-widest">Task Jatuh Tempo</h2>

            @if ($overdueTasks->count() > 0)
                <p class="text-xs font-semibold text-red-400/70 uppercase tracking-widest mb-3">Terlambat</p>
                @foreach ($overdueTasks as $task)
                    <a href="{{ route('tasks.show', $task) }}"
                        class="flex items-center justify-between py-2.5 border-b border-white/5 last:border-0 group">
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-red-400 flex-shrink-0"></div>
                            <span
                                class="text-sm text-white/50 group-hover:text-blue-300 transition">{{ $task->title }}</span>
                        </div>
                        <span class="text-xs text-red-400">{{ $task->due_date->diffForHumans() }}</span>
                    </a>
                @endforeach
                <div class="mb-4"></div>
            @endif

            @if ($upcomingTasks->count() > 0)
                <p class="text-xs font-semibold text-white/30 uppercase tracking-widest mb-3">Upcoming</p>
                @foreach ($upcomingTasks as $task)
                    <a href="{{ route('tasks.show', $task) }}"
                        class="flex items-center justify-between py-2.5 border-b border-white/5 last:border-0 group">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-1.5 h-1.5 rounded-full {{ $task->due_date->isToday() ? 'bg-amber-400' : 'bg-blue-500/50' }} flex-shrink-0">
                            </div>
                            <span
                                class="text-sm text-white/50 group-hover:text-blue-300 transition">{{ $task->title }}</span>
                        </div>
                        <span
                            class="text-xs {{ $task->due_date->isToday() ? 'text-amber-400 font-semibold' : 'text-white/30' }}">
                            {{ $task->due_date->isToday() ? 'Hari ini!' : $task->due_date->diffForHumans() }}
                        </span>
                    </a>
                @endforeach
            @endif

            @if ($overdueTasks->count() === 0 && $upcomingTasks->count() === 0)
                <div class="text-center py-8">
                    <div class="text-3xl mb-2">🎉</div>
                    <p class="text-sm text-white/30">Tidak ada task yang jatuh tempo.</p>
                </div>
            @endif
        </div>
    </div>

@endsection
