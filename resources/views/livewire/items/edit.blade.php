<?php

use Livewire\Volt\Component;
use App\Models\Item;
use Illuminate\Validation\Rule;

new class extends Component {

    public Item $item;
    public $name;

    public function mount($id)
    {
        $this->item = Item::findOrFail($id);
        $this->name = $this->item->name;
    }

    public function update()
    {
        // Validate the input
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        // Update the item
        $this->item->name = $this->name;
        $this->item->save();

        // Redirect with a success message
        session()->flash('message', 'Item updated successfully.');
        return $this->redirect('/items', navigate: true);
    }
}; ?>

<div>
    <div class="max-w-sm mx-auto">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit="update">
            <flux:input wire:model="name" label="{{ __('Name') }}" type="text" value="{{$item->name}}" name="name" required />

            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <button type="submit"
                class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300
                font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700
                dark:focus:ring-gray-700 dark:border-gray-700">Update</button>
        </form>
    </div>
</div>
