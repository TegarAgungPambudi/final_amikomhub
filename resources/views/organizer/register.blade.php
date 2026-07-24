@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-16">
    <div class="bg-white rounded-[2rem] border border-orange-100 p-8 shadow-sm">
        <h1 class="text-3xl font-black text-slate-900">Daftarkan Organisasi Anda</h1>
        <p class="text-slate-500 mt-2">Buat ruang kerja khusus untuk HIMA, kepanitiaan, atau organisasi Anda.</p>

        <form action="{{ route('organizer.register.store') }}" method="POST" class="mt-8 space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Organisasi</label>
                <input type="text" name="name" required class="w-full px-5 py-4 border border-slate-200 rounded-2xl" placeholder="Contoh: HIMA Teknik Informatika">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Slug Organisasi</label>
                <input type="text" name="slug" class="w-full px-5 py-4 border border-slate-200 rounded-2xl" placeholder="hima-teknik-informatika">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="4" class="w-full px-5 py-4 border border-slate-200 rounded-2xl" placeholder="Jelaskan organisasi Anda"></textarea>
            </div>
            <button type="submit" class="w-full py-4 bg-orange-500 text-white rounded-2xl font-black">Buat Organisasi & Masuk ke Dashboard</button>
        </form>
    </div>
</div>
@endsection
