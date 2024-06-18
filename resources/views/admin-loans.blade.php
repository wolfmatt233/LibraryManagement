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
                        <div class="flex">
                            <form method="get" action="{{ route('editLoan', $loan->id) }}" class="mr-1">
                                <x-primary-button class="hover:text-yellow-300">
                                    <i class="fa-solid fa-pencil"></i>
                                </x-primary-button>
                            </form>
                            <x-danger-button x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-loan-deletion-{{$loan->id}}')">
                                <i class="fa-solid fa-trash"></i>
                            </x-danger-button>
                        </div>
                </div>
                <x-modal name="confirm-loan-deletion-{{$loan->id}}" focusable>
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
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
