<?php

use Livewire\Volt\Component;
use App\Models\Item;
new class extends Component {
    //
    public $name='';
    public function store(){
        $this->validate([
            'name' => 'required|string|max:255',
        ]);


            $item = new Item();
            $item->name = $this->name;
            $item->save();
            return $this->redirect('/items', navigate: true);

    }
}; ?>






<div class="max-w-sm mx-auto">
    <form wire:submit="store">
        <flux:input wire:model="name" label="{{ __('Name') }}" type="text" name="name" required  />

        {{-- <button type="submit" class="text-white bg-blue-700  hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button> --}}
        {{-- <button type="submit"
        class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300
         font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700
         dark:focus:ring-gray-700 dark:border-gray-700">Create</button> --}}
         <flux:button type="submit" >Save</flux:button>
      </form>
</div>



