<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin: Edit Book') }}
        </h2>
        <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-book-deletion')">
            Delete Book <i class="fa-solid fa-trash ml-1"></i>
        </x-danger-button>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form class="flex flex-col" method="post" action="{{ route('updateBook', $book->id) }}"
                        enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <x-input-label for="title">Title</x-input-label>
                        <x-text-input class="mb-3" type="text" value="{{ $book->title }}" name="title"
                            required />

                        <x-input-label for="author">Author</x-input-label>
                        <x-text-input class="mb-3" type="text" value="{{ $book->author }}" name="author"
                            required />

                        <x-input-label for="genre">Genre</x-input-label>
                        <x-text-input class="mb-3" type="text" value="{{ $book->genre }}" name="genre"
                            required />

                        <x-input-label for="description">Description</x-input-label>
                        <textarea class="mb-3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text"
                            value="{{ $book->description }}" name="description" required rows="5">{{ $book->description }}</textarea>

                        <x-input-label for="isbn">ISBN</x-input-label>
                        <x-text-input class="mb-3" type="text" value="{{ $book->isbn }}" name="isbn"
                            required />

                        <x-input-label for="publisher">Publisher</x-input-label>
                        <x-text-input class="mb-3" type="text" value="{{ $book->publisher }}" name="publisher"
                            required />

                        <x-input-label for="published">Published</x-input-label>
                        <x-text-input class="mb-3" type="date" value="{{ $book->published }}" name="published"
                            required />

                        <x-input-label for="num_available">Copies Available</x-input-label>
                        <x-text-input class="mb-3" type="text" value="{{ $book->num_available }}"
                            name="num_available" required />

                        <div class="flex flex-col mb-3">
                            <img src="{{ asset('storage/images/' . $book->image) }}" alt="cover" class="w-32" />
                            <x-secondary-button id="imageChangeBtn" class="w-32">Change Image?</x-secondary-button>
                            <div id="fileInput" class="mt-2"></div>
                        </div>

                        <x-primary-button>Update Book</x-primary-button>
                    </form>
                </div>

                <x-modal name="confirm-book-deletion" focusable>
                    <form method="post" action="{{ route('deleteBook', $book->id) }}" class="p-6">
                        @csrf
                        @method('delete')

                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Are you sure you want to delete this book?') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Once the book is deleted, all of its data will be permanently deleted. This cannot be reversed.') }}
                        </p>

                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <x-danger-button class="ms-3">
                                {{ __('Delete Book') }}
                            </x-danger-button>
                        </div>
                    </form>
                </x-modal>
            </div>
        </div>
    </div>
</x-app-layout>
