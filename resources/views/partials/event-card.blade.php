@php
    $isPast = $event->date < now();
@endphp

<div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden {{ $isPast ? 'opacity-85' : '' }}">
    
    <div class="relative overflow-hidden aspect-[3/4] bg-slate-100">
        <img
            src="{{ $event->poster_url }}"
            alt="{{ $event->title }}"
            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        
        <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-orange-600">
            {{ $event->category->name }}
        </div>

        @if($isPast)
            <div class="absolute top-4 right-4 px-3 py-1 bg-slate-800/80 backdrop-blur rounded-lg text-xs font-bold text-white">
                Selesai
            </div>
        @endif

        @php
            $eventAvgRating = $event->averageRating();
            $eventReviewCount = $event->reviews()->count();
        @endphp
        @if($eventReviewCount > 0)
            <div class="absolute bottom-3 left-3 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                {{ number_format($eventAvgRating, 1) }}
            </div>
        @endif
    </div>

    <div class="p-6">
        <h3 class="text-xl font-bold mb-2 group-hover:text-orange-600 transition">
            {{ $event->title }}
        </h3>

        <div class="flex items-center gap-2 text-slate-500 text-sm mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>
                {{ \Carbon\Carbon::parse($event->date)->format('d-m-Y H:i') }}
            </span>
        </div>

        @if($event->organization)
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-3">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <a href="{{ route('organizer.profile', $event->organization->slug) }}" class="hover:text-orange-600 transition font-medium">
                    {{ $event->organization->name }}
                </a>
            </div>
        @endif

        <div class="flex justify-between items-center pt-4 border-t">
            <span class="text-2xl font-black text-orange-600">
                Rp {{ number_format($event->price, 0, ',', '.') }}
            </span>

            <a href="{{ route('events.show', $event->id) }}" class="px-5 py-2 bg-orange-50 text-orange-600 rounded-xl font-bold hover:bg-orange-600 hover:text-white transition">
                Lihat Detail
            </a>
        </div>
    </div>
</div>

