@extends('layouts.app')

@section('title', 'Welcome to EcoNexus')

@section('content')
<div class="relative bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 pt-20">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl font-serif">
                        <span class="block xl:inline">Connecting for a</span>
                        <span class="block text-[var(--color-eco-green)] xl:inline">plastic-free future</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        EcoNexus bridges the gap between those proposing sustainable alternatives and the organizations that can make them a reality. Join the movement to reduce plastic waste.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-[var(--color-eco-green)] bg-[var(--color-eco-accent)] hover:bg-opacity-90 md:py-4 md:text-lg md:px-10 transition">
                                Get started
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="{{ route('explore.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-[var(--color-eco-green)] bg-green-100 hover:bg-green-200 md:py-4 md:text-lg md:px-10 transition">
                                Explore ideas
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<div class="bg-[var(--color-eco-bg)] py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center mb-12">
            <h2 class="text-base text-[var(--color-eco-green)] font-semibold tracking-wide uppercase">Features</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl font-serif">
                A better way to collaborate
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <x-card title="Propose Ideas" image="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80">
                Submit your innovative plastic reduction solutions. Get feedback and validation from the community.
            </x-card>
            
            <x-card title="Connect" image="https://images.unsplash.com/photo-1528323273322-d81458248d40?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80">
                Recycling agencies and awareness organizations can find promising projects to fund or support.
            </x-card>
            
            <x-card title="Make an Impact" image="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80">
                Track the progress of adopted ideas and see real-world environmental impact.
            </x-card>
        </div>
    </div>
</div>
@endsection
