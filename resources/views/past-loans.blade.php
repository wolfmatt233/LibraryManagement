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
                    <form method="GET" action="{{ route('pastLoans') }}" class="flex items-center  mb-5">
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
                            <img src="{{ $loan->book->image }}" class="mr-2" />
                            <a href="" class="mr-2">{{ $loan->book->title }}</a>
                            <p class="mr-2">Borrowed: {{ $loan->borrow_date }}</p>
                            <p class="mr-2">Due Date: {{ $loan->due_date }}</p>
                            <p>Returned: {{ $loan->return_date }}</p>
                        </div>
                        <p>Returned Succuessfully</p>
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
