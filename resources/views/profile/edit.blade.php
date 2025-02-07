<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>
    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-semibold mb-4">{{ __('Profile Information') }}</h3>
                
                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700" for="name">
                        {{ __('Name') }}
                    </label>
                    <input value="{{ $user->name }}" disabled class="block mt-1 w-full rounded-md shadow-sm border-gray-300" />
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700" for="email">
                        {{ __('Email') }}
                    </label>
                    <input value="{{ $user->email }}" disabled class="block mt-1 w-full rounded-md shadow-sm border-gray-300" />
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
