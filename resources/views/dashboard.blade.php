<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Loans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('dashboard') }}" class="flex items-center  mb-5">
                        <x-text-input name="search" placeholder="Search loans here..." class="mr-3"
                            value="{{ $search }}" />
                        <x-primary-button>Search</x-primary-button>
                    </form>
                    @forelse($loans as $key=>$loan)
                        @if ($key % 2 != 0)
                            <div class="p-3 flex justify-between items-center">
                            @else
                                <div class="bg-gray-100 p-3 flex justify-between items-center">
                        @endif
                        <div class="flex">
                            <a href="{{ route('getBook', $loan->id) }}">
                                <img src="{{ URL('/images/' . $loan->book->image) }}" class="mr-2 w-14" />
                            </a>
                            <div>
                                <a class="mr-2 underline" href="{{ route('getBook', $loan->id) }}">
                                    {{ $loan->book->title }}
                                </a>
                                @if ($loan->date_difference < 0)
                                    <p class="text-red-500">Loan overdue by {{ $loan->date_difference *= -1 }} days!</p>
                                    <p class="text-red-500">Due date: {{ $loan->due_date }}</p>
                                @else
                                    <p>Due in {{ $loan->date_difference }} days</p>
                                @endif
                            </div>
                        </div>
                        <form method="POST" action="{{ route('removeLoan', $loan->id) }}">
                            @csrf
                            <x-primary-button>Return book</x-primary-button>
                        </form>
                </div>
            @empty
                <p>No loans found.</p>
                @endforelse
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
