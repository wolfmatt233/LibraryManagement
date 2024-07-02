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
                    <form method="GET" action="{{ route('books') }}" class="flex w-full mb-8 sm:flex-row flex-col">
                        <x-text-input name="search" placeholder="Search loans here..." value="{{ $search }}"
                            class="w-full mr-3 sm:mb-0 mb-2" />
                        <x-primary-button class="sm:justify-normal justify-center">Search</x-primary-button>
                    </form>
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
