@extends('layouts.app')
@section('title', 'Tambah Task')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white" style="font-family:'Syne',sans-serif;">Tambah Task</h1>
        <p class="text-white/40 text-sm mt-1">Isi detail task yang ingin ditambahkan</p>
    </div>
    <div class="navy-card rounded-2xl p-6">
        <form method="POST" action="{{ route('tasks.store') }}" class="space-y-5">
            @csrf
            @include('tasks._form')
            <button type="submit" class="btn-primary w-full py-3 rounded-xl text-sm font-semibold text-white mt-2">
                Simpan Task
            </button>
        </form>
    </div>
</div>
@endsection
