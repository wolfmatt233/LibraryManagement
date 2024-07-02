<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin: Add Book') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form class="flex flex-col" method="post" action="{{ route('createBook') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <x-input-label for="title">Title</x-input-label>
                        <x-text-input class="mb-3" type="text" name="title" required />

                        <x-input-label for="author">Author</x-input-label>
                        <x-text-input class="mb-3" type="text" name="author" required />

                        <x-input-label for="genre">Genre</x-input-label>
                        <x-text-input class="mb-3" type="text" name="genre" required />

                        <x-input-label for="description">Description</x-input-label>
                        <textarea class="mb-3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text"
                            name="description" required rows="5"></textarea>

                        <x-input-label for="isbn">ISBN</x-input-label>
                        <x-text-input class="mb-3" type="text" name="isbn" required />

                        <x-input-label for="publisher">Publisher</x-input-label>
                        <x-text-input class="mb-3" type="text" name="publisher" required />

                        <x-input-label for="published">Published</x-input-label>
                        <x-text-input class="mb-3" type="date" name="published" required />

                        <x-input-label for="num_available">Copies Available</x-input-label>
                        <x-text-input class="mb-3" type="text" name="num_available" required />

                        <x-input-label for="image">Image</x-input-label>
                        <x-text-input class="mb-3" type="file" name="image" required />

                        <x-primary-button>Add Book</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
