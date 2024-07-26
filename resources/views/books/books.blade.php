<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Books') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('books') }}">
                        <div class="flex w-full mb-3 sm:flex-row flex-col">
                            <x-text-input name="search" placeholder="Search loans here..." value="{{ $search }}"
                                class="w-full mr-3 sm:mb-0 mb-2" />
                            <x-primary-button class="sm:justify-normal justify-center">Search</x-primary-button>
                        </div>

                        <x-primary-button class="mr-3">Filter</x-primary-button>
                        @if (!$sort)
                            <select name="sort" id="sort">
                                <option disabled selected>Sort Alphabetically</option>
                                <option value="asc">Sort Ascending &uarr;</option>
                                <option value="desc">Sort Descending &darr;</option>
                            </select>
                        @elseif($sort == 'asc')
                            <select name="sort" id="sort">
                                <option disabled>Sort Alphabetically</option>
                                <option value="asc" selected>Sort Ascending &uarr;</option>
                                <option value="desc">Sort Descending &darr;</option>
                            </select>
                        @elseif ($sort == 'desc')
                            <select name="sort" id="sort">
                                <option disabled>Sort Alphabetically</option>
                                <option value="asc">Sort Ascending &uarr;</option>
                                <option value="desc" selected>Sort Descending &darr;</option>
                            </select>
                        @endif
                    </form>

                    <hr class="my-3">

                    <div class="grid grid-cols-[repeat(auto-fill,10rem)] justify-center gap-20">
                        @forelse ($books as $key=>$book)
                            <a class="mr-5 border" href="{{ route('getBook', $book->id) }}">
                                <img src="{{ asset('storage/images/' . $book->image) }}" />
                                <div class="p-1">
                                    <p class="text-sm">{{ $book->title }}</p>
                                    <p class="text-xs">by {{ $book->author }}</p>
                                </div>
                            </a>
                        @empty
                            <p>No books.</p>
                        @endforelse
                    </div>
                    {{ $books->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
