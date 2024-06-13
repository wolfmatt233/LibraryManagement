<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center">
        <h1 class="text-2xl mb-3">Welcome to Easy Lib!</h1>
        <p>Here, you can get all of your library needs. Search and filter through our extensive library of books, then
            find one to borrow. Once you're done, return it with one click! Sign up today!</p>

        @if (Route::has('login'))
            @auth
                <div class="mt-3">
                    <p class="text-lg mb-1">Welcome, {{ Auth::user()->name }}!</p>
                    <form method="GET" action="{{ route('dashboard') }}">
                        <x-primary-button>Go To Your Dashboard</x-primary-button>
                    </form>
                </div>
            @else
                <div class="flex items-center justify-evenly w-full mt-3">
                    <form method="GET" action="{{ route('register') }}">
                        <x-primary-button>Register</x-primary-button>
                    </form>

                    <p>or</p>

                    <form method="GET" action="{{ route('login') }}">
                        <x-primary-button>Log In</x-primary-button>
                    </form>
                </div>
            @endauth
        @endif
    </div>

</x-guest-layout>
