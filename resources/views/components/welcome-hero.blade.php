<section class="text-center max-w-3xl animate-fade-in-up transition-opacity duration-700 ease-out px-4">
    <!-- Animated Icon / Logo -->
    <div class="flex justify-center mb-8">
        <img src="https://laravel.com/img/logomark.min.svg"
             alt="Laravel Logo"
             class="h-16 w-16 animate-pulse dark:invert"
        >
    </div>

    <!-- Headline -->
    <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-[#1B263B] dark:text-white mb-4 leading-snug">
        Organize Your Adventures <br class="hidden sm:inline"> with Itinerary Planner
    </h1>

    <!-- Subheadline -->
    <p class="text-base sm:text-lg text-[#495057] dark:text-[#DDE2E5] mb-6">
        Create, visualize, and manage daily travel plans in a flexible and intuitive planner. From destinations to notes and time blocks — you’re in control.
    </p>

    <!-- CTA Buttons -->
    <div class="flex flex-col sm:flex-row justify-center gap-4">
        <a href="{{ route('login') }}"
           class="bg-[#1E3A8A] text-white px-6 py-3 rounded-md font-medium shadow hover:bg-[#2C5282] transition duration-300">
            Start Planning
        </a>
        <a href="#features"
           class="border border-[#1E3A8A] text-[#1E3A8A] px-6 py-3 rounded-md font-medium hover:bg-[#F0F4FF] dark:hover:bg-[#2A2E45] transition duration-300">
            Explore Features
        </a>
    </div>

    <!-- Interactive Feature Icons -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-10 text-sm text-[#343A40] dark:text-[#E5E7EB]">
        <div class="flex flex-col items-center">
            <svg class="w-10 h-10 text-[#1E3A8A] mb-2" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M8 7V3M16 7V3M3 11H21M5 20H19C20.1 20 21 19.1 21 18V7H3V18C3 19.1 3.9 20 5 20Z" />
            </svg>
            <span>Daily Planning</span>
        </div>
        <div class="flex flex-col items-center">
            <svg class="w-10 h-10 text-[#1E3A8A] mb-2" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M12 8C10.89 8 10 7.11 10 6C10 4.89 10.89 4 12 4C13.11 4 14 4.89 14 6C14 7.11 13.11 8 12 8ZM6 22V20C6 18.9 6.9 18 8 18H16C17.1 18 18 18.9 18 20V22M12 8V18" />
            </svg>
            <span>Visual Overview</span>
        </div>
        <div class="flex flex-col items-center">
            <svg class="w-10 h-10 text-[#1E3A8A] mb-2" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M5 3L19 12L5 21V3Z" />
            </svg>
            <span>Notes & Highlights</span>
        </div>
        <div class="flex flex-col items-center">
            <svg class="w-10 h-10 text-[#1E3A8A] mb-2" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M10 21V3H14V21H10Z" />
            </svg>
            <span>Weekly View</span>
        </div>
    </div>

    <!-- Scroll Cue -->
    <div class="mt-12">
        <a href="#features" class="inline-flex flex-col items-center animate-bounce text-[#1E3A8A] dark:text-white text-sm">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 9L12 16L5 9" />
            </svg>
            <span>Scroll to learn more</span>
        </a>
    </div>
</section>
