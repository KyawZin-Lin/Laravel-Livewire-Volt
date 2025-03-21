<?php

use Livewire\Volt\Component;
use App\Models\User;
new class extends Component {
    public $search = '';
    public function with(){
        $query= User::query();

        if (!empty($this->search)) {
            $query->where('name', 'LIKE', '%' . $this->search . '%')->orWhere('email', 'LIKE', '%' . $this->search . '%')->orWhereHas('roles', function ($query) {
                $query->where('name', 'LIKE', '%' . $this->search . '%');
            });
        }
        return[
            'users'=>$query->paginate(),
    ];
    }
}; ?>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="flex justify-between items-center mb-4">
        <a href="/users/create" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" wire:navigate>
            Create User
        </a>
        <div class="w-1/3">
            <input type="text" wire:model.live="search"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Search users...">
        </div>
    </div>

    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">ID</th>
                <th scope="col" class="px-6 py-3">Name</th>
                <th scope="col" class="px-6 py-3">Email</th>
                <th scope="col" class="px-6 py-3">Role</th>
                <th scope="col" class="px-6 py-3">Created At</th>
                <th scope="col" class="px-6 py-3">Updated At</th>
                <th scope="col" class="px-6 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">{{ $user->id }}</td>
                    <td class="px-6 py-4">{{ $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4">{{ $user->roles->pluck('name')->implode(', ') }}</td>
                    <td class="px-6 py-4">{{ $user->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-6 py-4">{{ $user->updated_at->format('Y-m-d H:i') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="/users/{{ $user->id }}/edit" wire:navigate>
                                <button
                                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Edit</button>
                            </a>
                            <button wire:confirm="Are you sure you want to delete this user?"
                                wire:click="delete({{ $user->id }})"
                                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
