<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads; // Import the trait once
use App\Models\Item;

new #[Layout('components.layouts.user-app')] class extends Component {
    use WithFileUploads; // Use the trait only once

    public $image;
    public $name;
    public $sku;
    public $unit;
    public $brand;
    public $type = [];

    public function save()
    {
        // dd($this->type, $this->name);
        $item = new Item();
        $item->name = $this->name;
        $item->sku = $this->sku;
        $item->unit = $this->unit;
        $item->brand = $this->brand;
        $item->type = json_encode($this->type); // Convert array to JSON before storing

        if ($this->image) {
            $imagePath = $this->image->storeAs('item-images', $this->image->getClientOriginalName(), 'public');
            $item->image = $imagePath;
        }

        $item->save();

        // Reset inputs after saving
        $this->reset(['name', 'sku', 'unit', 'brand', 'type', 'image']);

        session()->flash('message', 'Item added successfully!');
    }
};
?>

<div>
    <h1>New Item</h1>
    <br>
    <form wire:submit="save">
        <!-- Container for the form -->
        <div class="flex flex-col space-y-6">

            <!-- First Row: Type -->
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Type</label>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <input id="goods" type="checkbox" wire:model="type" value="goods"
                            class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" />
                        <label for="goods"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Goods</label>
                    </div>
                    <div class="flex items-center">
                        <input id="service" type="checkbox" wire:model="type" value="service"
                            class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" />
                        <label for="service"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Service</label>
                    </div>
                </div>
            </div>

            <!-- Second Row: Name -->
            <div class="mb-6">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                <input type="text" wire:model="name" id="name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Enter name" required />
            </div>

            <!-- Third Row: SKU -->
            <div class="mb-6">
                <label for="sku" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SKU</label>
                <input type="text" wire:model="sku" id="sku"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Enter SKU" required />
            </div>

            <!-- Fourth Row: Unit and Brand (2 columns) -->
            <div class="flex space-x-4 mb-6">
                <div class="flex-1">
                    <label for="unit"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Unit</label>
                    <input type="text" wire:model="unit" id="unit"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter unit" required />
                </div>
                <div class="flex-1">
                    <label for="brand"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Brand</label>
                    <input type="text" wire:model="brand" id="brand"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter brand" required />
                </div>
            </div>

            <!-- Fifth Row: Image -->
            <div class="mb-6">
                <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image</label>
                <input type="file" wire:model="image" id="image"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    accept="image/*" required />
            </div>

        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
    </form>
</div>
