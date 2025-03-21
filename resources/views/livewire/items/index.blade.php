<?php

use App\Models\Item;
use Livewire\Volt\Component;

new class extends Component {
    public $items = [];
    public $search = '';

    public function mount()
    {
        $this->items = Item::all();
    }

    public function create()
    {
        return $this->redirect('/items/create', navigate: true);
    }

    public function delete($id)
    {
        $item = Item::find($id); // Retrieve the instance
        if ($item) {
            $item->saleInformation->delete();
            $item->purchaseInformation->delete();

            $item->delete(); // Call delete() on the instance
        }
        $this->mount();
    }

    public function search()
    {
        $this->items = Item::where('name', 'LIKE', '%' . $this->search . '%')->get();
    }

    public function updatedSearch()
    {
        $this->search();
    }
}; ?>

<div class="relative overflow-x-auto">
    <div class="flex m-1">
        <a href="/items/create" class="w-1/2" wire:navigate>
            <flux:button>Create</flux:button>
        </a>
        <flux:input class="w-2/3" wire:model.live="search" icon="magnifying-glass" placeholder="Search Items" />
    </div>

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">ID</th>
                <th scope="col" class="px-6 py-3">Name</th>
                <th scope="col" class="px-6 py-3">SKU</th>
                {{-- <th scope="col" class="px-6 py-3">Image</th> --}}


                <th scope="col" class="px-6 py-3">Created At</th>
                <th scope="col" class="px-6 py-3">Updated At</th>
                <th scope="col" class="px-6 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $item->id }}
                    </th>
                    <td class="px-6 py-4"> <a href="/items/{{ $item->id }}/show" class="text-blue-500 hover:underline" wire:navigate>{{ $item->name }}</a></td>
                    <td class="px-6 py-4">{{ $item->sku }}</td>
                    {{-- <td class="px-6 py-4">
                        @foreach (json_decode($item->images) as $imagePath)
                            <img src="{{ asset('storage/' . $imagePath) }}" alt="Saved Image"
                                class="w-24 h-24 object-cover rounded-lg">
                        @endforeach
                    </td> --}}
                    {{-- <td class="px-6 py-4">
                        <div class="flex gap-2">
                            @foreach (json_decode($item->images) as $imagePath)
                                <a href="{{ asset('storage/' . $imagePath) }}" data-fancybox="gallery-{{ $item->id }}" data-caption="{{ $item->name }}">
                                    <img src="{{ asset('storage/' . $imagePath) }}" alt="Saved Image" class="w-24 h-24 object-cover rounded-lg hover:opacity-75 transition-opacity">
                                </a>
                            @endforeach
                        </div>
                    </td> --}}

                    <td class="px-6 py-4">{{ $item->created_at }}</td>
                    <td class="px-6 py-4">{{ $item->updated_at }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="/items/{{ $item->id }}/edit" wire:navigate>
                                <flux:button variant="primary">Edit</flux:button>
                            </a>
                            <flux:button wire:confirm="Are you sure you want to delete this item?"
                                wire:click="delete({{ $item->id }})" variant="danger">Delete</flux:button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>





