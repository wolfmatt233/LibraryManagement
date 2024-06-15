<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin: All Loans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('viewAll') }}" class="flex items-center  mb-5">
                        <x-text-input name="search" placeholder="Search loans here..." value="{{ $search }}"
                            class="mr-3" />
                        <x-primary-button>Search</x-primary-button>
                    </form>
                    @forelse ($loans as $key=>$loan)
                        @if ($key % 2 != 0)
                            <div class="p-3 flex justify-between items-center">
                            @else
                                <div class="bg-gray-100 p-3 flex justify-between items-center">
                        @endif
                        <div class="flex">
                            <img src="{{ $loan->book->image }}" class="mr-2" />
                            <p class="mr-2">User: {{ $loan->user }}</p>
                            <h1 class="mr-2">{{ $loan->book->title }}</h1>
                            <p class="mr-2">Status: {{ $loan->status }}</p>
                            @if ($loan->status == 'borrowed')
                                <p>Due: {{ $loan->due_date }}</p>
                            @else
                                <p>Returned: {{ $loan->return_date }}</p>
                            @endif
                        </div>
                        <form method="get" action="{{ route('editLoan', $loan->id) }}">
                            <x-primary-button>Edit Loan</x-primary-button>
                        </form>
                </div>
            @empty
                <p>No loans</p>
                @endforelse
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
