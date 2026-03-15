@extends('layouts.app')
@section('title', $task->title)

@section('content')

    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('tasks.index') }}" class="text-sm text-white/40 hover:text-blue-300 transition">← Kembali</a>
        <a href="{{ route('tasks.edit', $task) }}"
            class="text-sm text-white/50 border border-white/10 px-3 py-1.5 rounded-lg hover:bg-white/5 hover:text-blue-300 hover:border-blue-500/30 transition">
            ✏️ Edit Task
        </a>
    </div>

    <div class="navy-card rounded-2xl p-6 mb-4">
        <div class="flex items-start gap-4">
            <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="mt-1">
                @csrf @method('PATCH')
                <button type="submit"
                    class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition
                           {{ $task->is_completed ? 'bg-blue-600 border-blue-600' : 'border-white/20 hover:border-blue-400' }}">
                    @if ($task->is_completed)
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    @endif
                </button>
            </form>

            <div class="flex-1">
                <h1 class="text-xl font-bold text-white {{ $task->is_completed ? 'line-through text-white/30' : '' }}"
                    style="font-family:'Syne',sans-serif;">
                    {{ $task->title }}
                </h1>
                @if ($task->description)
                    <p class="text-white/40 text-sm mt-1.5">{{ $task->description }}</p>
                @endif
                <div class="flex flex-wrap gap-1.5 mt-3">
                    @if ($task->category)
                        <span class="pill-blue text-xs px-2.5 py-0.5 rounded-lg">{{ $task->category->icon }}
                            {{ $task->category->name }}</span>
                    @endif
                    <span class="pill-blue text-xs px-2.5 py-0.5 rounded-lg">{{ ucfirst($task->priority) }}</span>
                    @if ($task->due_date)
                        <span class="pill-blue text-xs px-2.5 py-0.5 rounded-lg">📅
                            {{ $task->due_date->format('d M Y') }}</span>
                    @endif
                </div>

                @if ($task->subTasks->count() > 0)
                    <div class="mt-5 pt-5 border-t border-white/5">
                        <div class="flex justify-between text-xs text-white/30 mb-2">
                            <span>Progress</span>
                            <span>{{ $task->subTasks->where('is_completed', true)->count() }}/{{ $task->subTasks->count() }}
                                selesai</span>
                        </div>
                        <div class="w-full bg-white/5 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-700
                                    {{ $task->progress == 100 ? 'progress-green' : 'progress-blue' }}"
                                style="width: {{ $task->progress }}%">
                            </div>
                        </div>
                        <p class="text-xs text-white/30 mt-1.5">{{ $task->progress }}% selesai</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="navy-card rounded-2xl p-6">
        <h2 class="text-sm font-semibold text-white/60 mb-5 uppercase tracking-widest">Sub-task</h2>
        <div class="space-y-1 mb-4">
            @forelse($task->subTasks as $sub)
                <div class="flex items-center justify-between gap-3 py-3 border-b border-white/5 last:border-0">
                    <div class="flex items-start gap-3">
                        <form method="POST" action="{{ route('tasks.toggle', $sub) }}" class="mt-0.5">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="w-4 h-4 rounded-full border-2 flex items-center justify-center transition
                                       {{ $sub->is_completed ? 'bg-blue-600 border-blue-600' : 'border-white/20 hover:border-blue-400' }}">
                                @if ($sub->is_completed)
                                    <svg class="w-2 h-2 text-white" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </button>
                        </form>
                        <div>
                            <p class="text-sm text-white/70 {{ $sub->is_completed ? 'line-through text-white/20' : '' }}">
                                {{ $sub->title }}
                            </p>
                            @if ($sub->is_completed && $sub->completed_at)
                                <p class="text-xs text-emerald-500/60 mt-0.5">
                                    Selesai {{ $sub->completed_at->translatedFormat('l, d F Y') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <form method="POST" action="{{ route('tasks.subtasks.destroy', $sub) }}"
                        onsubmit="return confirm('Hapus sub-task ini?')">
                        @csrf @method('DELETE')
                        <button
                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-white/5 border border-white/10 text-white/30 hover:bg-red-500/20 hover:text-red-400 hover:border-red-500/30 transition text-xs">
                            🗑️
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-white/30 py-2">Belum ada sub-task.</p>
            @endforelse
        </div>

        @if (!$task->is_completed)
            <form method="POST" action="{{ route('tasks.subtasks.store', $task) }}"
                class="flex gap-2 pt-4 border-t border-white/5">
                @csrf
                <input type="text" name="title" placeholder="Tambah sub-task baru..."
                    class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-white/20 focus:outline-none focus:border-blue-500/50 transition @error('title') border-red-500/50 @enderror">
                <button type="submit" class="btn-primary px-4 py-2.5 rounded-xl text-sm font-semibold text-white">
                    + Tambah
                </button>
            </form>
            @error('title')
                <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        @else
            <p class="text-xs text-white/20 pt-4 border-t border-white/5">Task sudah selesai, sub-task tidak bisa ditambah.
            </p>
        @endif
    </div>
    {{-- Confetti & Celebration --}}
    <div id="confetti-container" class="fixed inset-0 pointer-events-none z-50 hidden"></div>

    <div id="celebration-toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden">
        <div class="flex items-center gap-3 px-6 py-4 rounded-2xl shadow-2xl"
            style="background: linear-gradient(135deg, #1d4ed8, #2563eb); border: 1px solid rgba(96,165,250,0.3);">
            <span class="text-2xl">🎉</span>
            <div>
                <p class="font-bold text-white text-sm" style="font-family:'Syne',sans-serif;">Semua task selesai!</p>
                <p class="text-blue-200 text-xs mt-0.5">Kerja bagus! Kamu udah menyelesaikan semua sub-task.</p>
            </div>
            <span class="text-2xl">🏆</span>
        </div>
    </div>

    <script>
        // Cek apakah progress 100% saat halaman dimuat
        const progress = {{ $task->progress }};
        const subCount = {{ $task->subTasks->count() }};

        if (progress === 100 && subCount > 0) {
            setTimeout(() => startCelebration(), 400);
        }

        function startCelebration() {
            showToast();
            launchConfetti();
        }

        // ── Toast ──
        function showToast() {
            const toast = document.getElementById('celebration-toast');
            toast.classList.remove('hidden');
            toast.style.animation = 'slideUpToast 0.5s cubic-bezier(0.16,1,0.3,1) both';

            setTimeout(() => {
                toast.style.animation = 'fadeOutToast 0.4s ease forwards';
                setTimeout(() => toast.classList.add('hidden'), 400);
            }, 3500);
        }

        // ── Confetti ──
        function launchConfetti() {
            const container = document.getElementById('confetti-container');
            container.classList.remove('hidden');

            const colors = ['#3b82f6', '#60a5fa', '#93c5fd', '#10b981', '#34d399', '#ffffff', '#fbbf24'];
            const totalPieces = 80;

            for (let i = 0; i < totalPieces; i++) {
                setTimeout(() => {
                    const piece = document.createElement('div');
                    const size = Math.random() * 8 + 4;
                    const color = colors[Math.floor(Math.random() * colors.length)];
                    const left = Math.random() * 100;
                    const delay = Math.random() * 0.5;
                    const duration = Math.random() * 2 + 2;
                    const isCircle = Math.random() > 0.5;

                    piece.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                background: ${color};
                border-radius: ${isCircle ? '50%' : '2px'};
                left: ${left}%;
                top: -10px;
                opacity: 0.85;
                animation: confettiFall ${duration}s ${delay}s ease-in forwards;
                transform: rotate(${Math.random() * 360}deg);
            `;

                    container.appendChild(piece);
                    setTimeout(() => piece.remove(), (duration + delay) * 1000 + 100);
                }, i * 20);
            }

            setTimeout(() => container.classList.add('hidden'), 5000);
        }

        // ── Animasi saat toggle subtask ──
        // Re-trigger confetti kalau progress baru capai 100% setelah toggle
        document.querySelectorAll('form[action*="toggle"]').forEach(form => {
            form.addEventListener('submit', function() {
                const pending = {{ $task->subTasks->where('is_completed', false)->count() }};
                if (pending === 1) {
                    // Satu subtask tersisa & mau dicentang = akan 100%
                    setTimeout(() => startCelebration(), 600);
                }
            });
        });
    </script>

    <style>
        @keyframes confettiFall {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.9;
            }

            100% {
                transform: translateY(110vh) rotate(720deg);
                opacity: 0;
            }
        }

        @keyframes slideUpToast {
            from {
                opacity: 0;
                transform: translate(-50%, 20px);
            }

            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }

        @keyframes fadeOutToast {
            from {
                opacity: 1;
                transform: translate(-50%, 0);
            }

            to {
                opacity: 0;
                transform: translate(-50%, 10px);
            }
        }
    </style>
@endsection
