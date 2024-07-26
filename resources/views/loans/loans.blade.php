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
                    @if ($errors->any())
                        <div class="cursor-pointer mb-3 flex" id='errorMsg'>
                            <p class="text-red-400">{{ $errors->first() }}</p>
                            <span class="ml-2 underline">&#x2613;</span>
                        </div>
                    @endif
                    <form method="GET" action="{{ route('loans') }}">
                        <div class="flex w-full mb-3 sm:flex-row flex-col">
                            <x-text-input name="search" placeholder="Search loans here..." value="{{ $search }}"
                                class="w-full mr-3 sm:mb-0 mb-2" />
                            <x-primary-button class="sm:justify-normal justify-center">Search</x-primary-button>
                        </div>

                        <x-primary-button class="mr-3">Filter</x-primary-button>

                        {{-- Sorting --}}
                        @if (!$sort)
                            <select name="sort" id="sort" class="mr-3">
                                <option disabled selected>Sort Alphabetically</option>
                                <option value="asc">Sort Ascending &uarr;</option>
                                <option value="desc">Sort Descending &darr;</option>
                            </select>
                        @elseif($sort == 'asc')
                            <select name="sort" id="sort" class="mr-3">
                                <option disabled>Sort Alphabetically</option>
                                <option value="asc" selected>Sort Ascending &uarr;</option>
                                <option value="desc">Sort Descending &darr;</option>
                            </select>
                        @elseif ($sort == 'desc')
                            <select name="sort" id="sort" class="mr-3">
                                <option disabled>Sort Alphabetically</option>
                                <option value="asc">Sort Ascending &uarr;</option>
                                <option value="desc" selected>Sort Descending &darr;</option>
                            </select>
                        @endif

                        {{-- Past loans --}}
                        <label for="pastLoans">View Past Loans</label>
                        @if ($past)
                            <input type="checkbox" name="pastLoans" id="pastLoans" checked>
                        @else
                            <input type="checkbox" name="pastLoans" id="pastLoans">
                        @endif
                    </form>

                    <hr class="my-3">

                    @forelse($loans as $key=>$loan)
                        @if ($key % 2 != 0)
                            <div class="p-3 flex justify-between sm:items-center sm:flex-row flex-col">
                            @else
                                <div class="bg-gray-100 p-3 flex justify-between sm:items-center sm:flex-row flex-col">
                        @endif
                        <div class="flex">
                            <a href="{{ route('getBook', $loan->book_id) }}">
                                <img src="{{ asset('storage/images/' . $loan->book->image) }}" class="mr-2 w-14" />
                            </a>
                            <div>
                                @if ($past)
                                    {{-- Returned --}}
                                    <a class="mr-2 underline" href="{{ route('getBook', $loan->book_id) }}">
                                        {{ $loan->book->title }}
                                    </a>
                                    <p>Borrowed: {{ $loan->borrow_date }}</p>
                                    <p>Due Date: {{ $loan->due_date }}</p>
                                @else
                                    {{-- Borrowed --}}
                                    <a class="mr-2 underline" href="{{ route('getBook', $loan->book_id) }}">
                                        {{ $loan->book->title }}
                                    </a>
                                    @if ($loan->date_difference < 0)
                                        <p class="text-red-500">Loan overdue by {{ $loan->date_difference *= -1 }}
                                            days!
                                        </p>
                                        <p class="text-red-500">Due date: {{ $loan->due_date }}</p>
                                    @else
                                        <p>Due in {{ $loan->date_difference }} days</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @if ($past)
                            <p>Returned {{ $loan->return_date }}</p>
                        @else
                            <form method="POST" action="{{ route('removeLoan', $loan->id) }}"
                                class="sm:mt-0 mt-4 sm:w-[133px] w-full">
                                @csrf
                                <x-primary-button class="w-full sm:w-[133px]">Return book</x-primary-button>
                            </form>
                        @endif
                </div>
            @empty
                <p class="ml-2">No loans found.</p>
                @endforelse
            </div>
            {{ $loans->appends($_GET)->links() }}
        </div>
    </div>
    </div>
</x-app-layout>
