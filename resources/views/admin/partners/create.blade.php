@extends('layouts.admin')

@section('content')
<main class="flex-1 p-10 overflow-y-auto">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-black">Tambah Partner Baru</h1>
            <p class="text-slate-500 font-medium">Daftarkan mitra baru ke dalam platform.</p>
        </div>
        <a href="{{ route('admin.partners.index') }}"
            class="px-6 py-3 bg-slate-200 text-slate-700 rounded-2xl font-bold hover:bg-slate-300 active:scale-95 transition">
            ← Kembali
        </a>
    </header>

    <div class="max-w-2xl bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-10">
        <form action="{{ route('admin.partners.store') }}" method="POST" class="space-y-8" enctype="multipart/form-data">
            @csrf

            <!-- Input Nama Partner -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">
                    Nama Partner <span class="text-red-600">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name') }}"
                       class="w-full px-5 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition"
                       placeholder="Masukkan nama partner (contoh: Tech Innovation Labs)"
                       required>
                @error('name')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input Logo File -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">
                    Upload Logo
                </label>
                <input type="file" 
                       name="logo" 
                       accept="image/*"
                       class="w-full px-5 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition"
                       >
                <p class="text-slate-500 text-sm mt-2">
                    Upload gambar jika ingin menyimpan file ke server.
                </p>
                @error('logo')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input URL Logo -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">
                    URL Logo
                </label>
                <input type="url" 
                       name="logo_url" 
                       value="{{ old('logo_url') }}"
                       class="w-full px-5 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition"
                       placeholder="https://placehold.co/200x200">
                <p class="text-slate-500 text-sm mt-2">
                    Isi URL jika ingin memakai gambar dari tautan eksternal.
                </p>
                @error('logo_url')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Preview Logo -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">Preview Logo</label>
                <div id="logo-preview" class="w-32 h-32 border-2 border-dashed border-slate-300 rounded-xl flex items-center justify-center bg-slate-50 overflow-hidden">
                    <span class="text-slate-400 text-sm text-center">Preview akan muncul di sini</span>
                </div>
            </div>

            <!-- Submit & Cancel Buttons -->
            <div class="flex gap-4 pt-6 border-t">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition">
                    Simpan Partner
                </button>
                <a href="{{ route('admin.partners.index') }}"
                   class="flex-1 px-6 py-3 bg-slate-100 text-slate-700 rounded-xl font-bold hover:bg-slate-200 active:scale-95 transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</main>

<script>
    const logoInput = document.querySelector('input[name="logo_url"]');
    const fileInput = document.querySelector('input[name="logo"]');
    const logoPreview = document.getElementById('logo-preview');
    const fallbackLogo = '{{ asset('assets/partner-placeholder.svg') }}';

    function renderImage(src) {
        logoPreview.innerHTML = `<img src="${src}" alt="Logo Preview" class="w-full h-full object-cover rounded-lg" onerror="this.onerror=null;this.src='${fallbackLogo}'">`;
    }

    logoInput.addEventListener('input', function() {
        if (this.value) {
            renderImage(this.value);
        } else {
            logoPreview.innerHTML = '<span class="text-slate-400 text-sm text-center">Preview akan muncul di sini</span>';
        }
    });

    fileInput.addEventListener('change', function() {
        const file = this.files && this.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(event) {
                renderImage(event.target.result);
            };

            reader.readAsDataURL(file);
            return;
        }

        if (logoInput.value) {
            renderImage(logoInput.value);
            return;
        }

        logoPreview.innerHTML = '<span class="text-slate-400 text-sm text-center">Preview akan muncul di sini</span>';
    });

    if (logoInput.value) {
        logoInput.dispatchEvent(new Event('input'));
    }
</script>
@endsection
