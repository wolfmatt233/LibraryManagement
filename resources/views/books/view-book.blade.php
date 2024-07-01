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
                    <div class="flex sm:flex-row sm:items-start flex-col items-center">
                        <div class="w-48 sm:mb-0 mb-5">
                            <img src="{{ URL('/images/' . $book->image) }}" />
                        </div>
                        <div class="ml-12 w-full">
                            <p class="text-3xl">{{ $book->title }}</p>
                            <p><i>by {{ $book->author }}</i></p>
                            <hr class="my-3">
                            <p class="text-xl mt-3">Description</p>
                            <p class="ml-2 mt-1">{{ $book->description }}</p>
                            <p class="text-xl mt-3">Information</p>
                            <div class="ml-2 mt-1">
                                <p><b>ISBN:</b> {{ $book->isbn }}</p>
                                <p><b>Published:</b> {{ $book->published }}</p>
                                <p><b>Publisher:</b> {{ $book->publisher }}</p>
                                <p><b>Genre:</b> {{ $book->genre }}</p>
                                <p><b>Copies available:</b> {{ $book->num_available }}</p>
                            </div>
                            <div class="mt-3">
                                @if ($borrowed != 'false')
                                    <form method="POST" action="{{ route('removeLoan', $borrowed) }}">
                                        @csrf
                                        <x-primary-button>Return book</x-primary-button>
                                    </form>
                                @else
                                    @if ($limited == 'true')
                                        <p
                                            class="border border-gray-300 rounded-md font-semibold text-sm text-gray-700 inline-flex p-1 shadow-sm">
                                            Max Borrow Limit Reached
                                        </p>
                                    @else
                                        @if ($book->num_available != 0)
                                            <form method="POST" action="{{ route('createLoan', $book->id) }}">
                                                @csrf
                                                <x-primary-button>Borrow</x-primary-button>
                                            </form>
                                        @elseif($book->num_available == 0)
                                            <form method="POST" action="{{ route('createHold', $book->id) }}">
                                                @csrf
                                                <p class="mb-2">No copies available. Try reserving the item.</p>
                                                <x-primary-button>Place Hold</x-primary-button>
                                            </form>
                                        @endif
                                    @endif
                                @endif

                                @if (Auth::user()->admin == true)
                                    <form method="get" action="{{ route('editBook', $book->id) }}">
                                        <x-primary-button class="hover:text-yellow-300 mt-2">
                                            Edit Book
                                            <i class="fa-solid fa-pencil ml-1"></i>
                                        </x-primary-button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
