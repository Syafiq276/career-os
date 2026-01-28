<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Test Page
        </h2>
    </x-slot>

    <x-slot name="content">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h1 class="text-2xl font-bold text-gray-900">This is a test!</h1>
                    <p class="mt-4 text-gray-600">If you can see this, the layout component is working.</p>
                </div>
            </div>
        </div>
    </x-slot>
</x-app-layout>
