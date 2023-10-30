<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                   @role('writer|admin')
                    <button class="btn btn-success">Create Data</button>
                    @endrole
                    <table class="table">
                    <thead>
                        <th>#</th>
                        <th>Title</th>
                        <th>Body</th>
                        <th>Action</th>
                    </thead>
                   @foreach ($posts as $post)
                   <tbody>
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->body }}</td>
                        @role('editor|writer|admin')
                        <td><button class="btn btn-primary">Edit</button></td>
                        @endrole
                    </tr>
                </tbody>
                   @endforeach
                   </table>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
