<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin: Edit Loan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form class="flex flex-col" method="post" action="{{ route('updateLoan', $loan->id) }}">
                        @csrf @method('PUT')
                        <x-input-label for="due_date">Due Date</x-input-label>
                        <x-text-input class="mb-3" type="date" value="{{ $loan->due_date }}" name="due_date"
                            required />
                        <x-input-label for="return_date">Return Date</x-input-label>
                        <x-text-input class="mb-3" type="date" value="{{ $loan->return_date }}"
                            name="return_date" />
                        <x-input-label for="status">Status</x-input-label>
                        <select name="status" class="mb-4">
                            @if ($loan->status == 'borrowed')
                                <option value="borrowed" selected="selected">Borrowed</option>
                            @else
                                <option value="borrowed">Borrowed</option>
                            @endif

                            @if ($loan->status == 'returned')
                                <option value="returned" selected="selected">Returned</option>
                            @else
                                <option value="returned">Returned</option>
                            @endif
                        </select>
                        <x-primary-button>Update</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
