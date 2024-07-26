<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Past Loans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('pastLoans') }}" class="flex w-full mb-5 sm:flex-row flex-col">
                        <x-text-input name="search" placeholder="Search loans here..." value="{{ $search }}"
                            class="w-full mr-3 sm:mb-0 mb-2" />
                        <x-primary-button class="sm:justify-normal justify-center">Search</x-primary-button>
                    </form>
                    @forelse($loans as $key=>$loan)
                        @if ($key % 2 != 0)
                            <div class="p-3 flex justify-between items-center">
                            @else
                                <div class="bg-gray-100 p-3 flex justify-between items-center">
                        @endif
                        <div class="flex">
                            <a href="{{ route('getBook', $loan->book_id) }}">
                                <img src="{{ asset('storage/images/' . $loan->book->image) }}" class="mr-2 w-14" />
                            </a>
                            <div>
                                <a class="mr-2 underline" href="{{ route('getBook', $loan->book_id) }}">
                                    {{ $loan->book->title }}
                                </a>
                                <p>Borrowed: {{ $loan->borrow_date }}</p>
                                <p>Due Date: {{ $loan->due_date }}</p>
                            </div>
                        </div>
                        <p>Returned {{ $loan->return_date }}</p>
                </div>
            @empty
                <p>No loans found.</p>
                @endforelse
            </div>
        </div>
    </div>
    </div>
    </div>
</x-app-layout>
