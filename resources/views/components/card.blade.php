<div {{ $attributes->merge(['class' => 'glass-card rounded-2xl overflow-hidden transition duration-300 hover:shadow-2xl']) }}>
    @if(isset($image))
        <img src="{{ $image }}" alt="{{ $imageAlt ?? 'Card Image' }}" class="w-full h-48 object-cover">
    @endif
    <div class="p-6">
        @if(isset($title))
            <h3 class="text-xl font-bold text-[var(--color-eco-green)] mb-2 font-serif">{{ $title }}</h3>
        @endif
        <div class="text-[var(--color-eco-slate)]">
            {{ $slot }}
        </div>
        @if(isset($footer))
            <div class="mt-4 pt-4 border-t border-gray-200">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
