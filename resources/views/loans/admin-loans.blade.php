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
                    <form method="GET" action="{{ route('viewAll') }}" class="flex w-full mb-5 sm:flex-row flex-col">
                        <x-text-input name="search" placeholder="Search loans here..." value="{{ $search }}"
                            class="w-full mr-3 sm:mb-0 mb-2" />
                        <x-primary-button class="sm:justify-normal justify-center">Search</x-primary-button>
                    </form>
                    @forelse ($loans as $key=>$loan)
                        @if ($key % 2 != 0)
                            <div class="p-3 flex justify-between items-center">
                            @else
                                <div class="bg-gray-100 p-3 flex justify-between items-center">
                        @endif
                        <div class="flex">
                            <a href="{{ route('getBook', $loan->book_id) }}">
                                <img src="{{ URL('/images/' . $loan->book->image) }}" class="mr-2 w-14" />
                            </a>
                            <div>
                                <a class="mr-2 underline" href="{{ route('getBook', $loan->book_id) }}">
                                    {{ $loan->book->title }}
                                </a>
                                <p class="mr-2"><b>User:</b> {{ $loan->user }}</p>
                                <p class="mr-2"><b>Status:</b> {{ $loan->status }}</p>
                                @if ($loan->status == 'borrowed')
                                    <p><b>Due:</b> {{ $loan->due_date }}</p>
                                @else
                                    <p><b>Returned:</b> {{ $loan->return_date }}</p>
                                @endif
                                @if ($loan->date_difference < 0 && $loan->status == 'borrowed')
                                    <p class="text-red-600">Loan Overdue</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center justify-center sm:flex-row flex-col">
                            <form method="get" action="{{ route('editLoan', $loan->id) }}"
                                class="sm:mr-1 mr-0 sm:mb-0 mb-1">
                                <x-primary-button class="hover:text-yellow-300">
                                    <i class="fa-solid fa-pencil"></i>
                                </x-primary-button>
                            </form>
                            <x-danger-button x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-loan-deletion-{{ $loan->id }}')">
                                <i class="fa-solid fa-trash"></i>
                            </x-danger-button>
                        </div>
                </div>
                <x-modal name="confirm-loan-deletion-{{ $loan->id }}" focusable>
                    <form method="post" action="{{ route('deleteLoan', $loan->id) }}" class="p-6">
                        @csrf
                        @method('delete')

                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Are you sure you want to delete this loan?') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Once the loan is deleted, all of its data will be permanently deleted. This cannot be reversed.') }}
                        </p>

                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <x-danger-button class="ms-3">
                                {{ __('Delete Loan') }}
                            </x-danger-button>
                        </div>
                    </form>
                </x-modal>
            @empty
                <p>No loans</p>
                @endforelse
                {{ $loans->appends($_GET)->links() }}
            </div>

        </div>
    </div>
    </div>
</x-app-layout>
