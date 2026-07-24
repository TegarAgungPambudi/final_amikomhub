@extends('layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-6 py-12">
        <!-- Back Button -->
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-orange-600 font-medium mb-8 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>

        <!-- Organizer Profile Header -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 md:p-12 mb-10">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                <!-- Avatar -->
                <div class="w-24 h-24 bg-orange-500 rounded-2xl flex items-center justify-center text-white font-black text-3xl shrink-0 shadow-lg shadow-orange-200">
                    {{ strtoupper(substr($organization->name, 0, 2)) }}
                </div>
                
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-3xl md:text-4xl font-black mb-2">{{ $organization->name }}</h1>
                    <p class="text-slate-500 mb-4 max-w-2xl">{{ $organization->description ?? 'Penyelenggara event terpercaya di Amikom' }}</p>
                    
                    <div class="flex flex-wrap justify-center md:justify-start gap-6">
                        <!-- Total Events -->
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-bold">{{ $totalEvents }}</span>
                            <span class="text-sm">Event</span>
                        </div>

                        <!-- Average Rating -->
                        <div class="flex items-center gap-2 text-slate-600">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="font-bold">{{ number_format($avgRating, 1) }}</span>
                            <span class="text-sm text-slate-400">({{ $totalReviews }} ulasan)</span>
                        </div>

                        <!-- Member Since -->
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm">Bergabung {{ $organization->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rating Breakdown -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 mb-10">
            <h3 class="text-xl font-black mb-6">Distribusi Rating</h3>
            <div class="space-y-3 max-w-lg">
                @for($i = 5; $i >= 1; $i--)
                    @php
                        $count = $ratingCounts[$i] ?? 0;
                        $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold text-slate-600 w-4">{{ $i }}</span>
                        <svg class="w-4 h-4 text-yellow-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-400 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-slate-500 w-8 text-right">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Events List -->
        <div class="mb-10">
            <h3 class="text-2xl font-black mb-6">Event oleh {{ $organization->name }}</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($events as $event)
                    <div class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                        <div class="relative overflow-hidden aspect-[3/4] bg-slate-100">
                            <img src="{{ $event->poster_url }}" alt="{{ $event->title }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            
                            <div class="absolute top-3 left-3 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-orange-600">
                                {{ $event->category->name ?? 'Event' }}
                            </div>
                            
                            <!-- Rating badge on poster -->
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

                        <div class="p-5">
                            <h4 class="font-bold mb-2 group-hover:text-orange-600 transition">{{ $event->title }}</h4>
                            
                            <div class="flex items-center gap-1 text-slate-500 text-xs mb-3">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $event->date ? $event->date->format('d M Y') : 'TBA' }}</span>
                            </div>

                            <div class="flex justify-between items-center pt-3 border-t">
                                <span class="text-lg font-black text-orange-600">
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                </span>
                                <a href="{{ route('events.show', $event->id) }}" class="text-xs font-bold text-orange-600 hover:underline">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-slate-400">
                        <p class="font-medium">Belum ada event yang diterbitkan.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Reviews -->
        <div>
            <h3 class="text-2xl font-black mb-6">Ulasan Terbaru untuk {{ $organization->name }}</h3>
            
            <div class="space-y-4">
                @forelse($reviews as $review)
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-sm shrink-0">
                                {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-3 mb-1">
                                    <p class="font-bold text-slate-800">{{ $review->user->name ?? 'Anonymous' }}</p>
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs font-medium text-orange-500 mb-2">
                                    pada event: <a href="{{ route('events.show', $review->event_id) }}" class="hover:underline">{{ $review->event->title ?? 'Event' }}</a>
                                </p>
                                @if($review->comment)
                                    <p class="text-slate-600">{{ $review->comment }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="font-medium">Belum ada ulasan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
@endsection

