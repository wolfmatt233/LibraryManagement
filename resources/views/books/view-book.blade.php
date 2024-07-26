<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($book->title) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex sm:flex-row sm:items-start flex-col items-center">
                        <div class="sm:w-1/5 w-48 sm:mb-0 mb-5">
                            <img src="{{ asset('storage/images/' . $book->image) }}" />
                            <div class="mt-3  w-full">
                                {{-- Loans & Holds --}}
                                @if ($borrowed != 'false')
                                    <form method="POST" action="{{ route('removeLoan', $borrowed) }}">
                                        @csrf
                                        <x-primary-button class="w-full">Return book</x-primary-button>
                                    </form>
                                @else
                                    @if ($limited == 'true')
                                        <p
                                            class="w-full border border-gray-300 rounded-md font-semibold text-sm text-gray-700 inline-flex p-1 shadow-sm">
                                            Maximum Borrow Limit Reached
                                        </p>
                                    @else
                                        @if ($book->num_available != 0)
                                            <form method="POST" action="{{ route('createLoan', $book->id) }}">
                                                @csrf
                                                <x-primary-button class="w-full">Borrow</x-primary-button>
                                            </form>
                                        @elseif($book->num_available == 0)
                                            @if ($holdWaiting == 'false')
                                                <form method="POST" action="{{ route('createHold', $book->id) }}">
                                                    @csrf
                                                    <p class="mb-2 w-full">No copies available. Try reserving the item.
                                                    </p>
                                                    <x-primary-button class="w-full">Place Hold</x-primary-button>
                                                </form>
                                            @else
                                                <p
                                                    class="w-full border border-gray-300 rounded-md font-semibold text-sm text-gray-700 inline-flex p-1 shadow-sm">
                                                    Your hold is waiting.
                                                </p>
                                                <form method="POST" action="{{ route('cancelHold', $book->id) }}">
                                                    @csrf
                                                    <x-primary-button class="w-full mt-2">Cancel Hold</x-primary-button>
                                                </form>
                                            @endif
                                        @endif
                                    @endif
                                @endif

                                {{-- Wishlist --}}
                                @if ($wishlist == 'false')
                                    <form method="POST" action="{{ route('addWishlist', $book->id) }}">
                                        @csrf
                                        <x-primary-button class="w-full mt-2">Add to Wishlist</x-primary-button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('removeWishlist', $book->id) }}">
                                        @csrf
                                        <x-primary-button class="w-full mt-2">Remove from Wishlist</x-primary-button>
                                    </form>
                                @endif

                                {{-- Admin --}}
                                @if (Auth::user()->admin == true)
                                    <form method="get" action="{{ route('editBook', $book->id) }}">
                                        <x-primary-button class="hover:text-yellow-300 mt-2 w-full">
                                            Edit Book
                                            <i class="fa-solid fa-pencil ml-1"></i>
                                        </x-primary-button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div class="sm:ml-12 m1-0 sm:w-4/5">
                            <p class="text-3xl">{{ $book->title }}</p>
                            <p><i>by {{ $book->author }}</i></p>
                            <hr class="my-3">
                            <p class="text-xl mt-3">Description</p>
                            <p class="ml-2 mt-1">{!! $book->description !!}</p>
                            <hr class="my-3">
                            <p class="text-xl mt-3">Information</p>
                            <div class="ml-2 mt-1">
                                <p><b>ISBN:</b> {{ $book->isbn }}</p>
                                <p><b>Published:</b> {{ $book->published }}</p>
                                <p><b>Publisher:</b> {{ $book->publisher }}</p>
                                <p><b>Genre:</b> {{ $book->genre }}</p>
                                <p><b>Copies available:</b> {{ $book->num_available }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
