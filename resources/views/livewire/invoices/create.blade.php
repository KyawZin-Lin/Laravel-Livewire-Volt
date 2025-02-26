<?php

use Livewire\Volt\Component;
use App\Models\Invoice;
use App\Models\Item;
use Illuminate\Support\Str;
new class extends Component {
    //
    public $name = '';
    public $item_id = '';

    public $items=[];

    public function mount(){
        $this->items = Item::all();
        $this->name=auth()->user()->name;
    }
    public function store() {
    $this->validate([
        'name' => 'required|string|max:255',
        'item_id' => 'required|exists:items,id'
    ]);

    $invoice = new Invoice();
    $invoice->user_id = auth()->id(); // Assign logged-in user
    $invoice->invoice_id = Str::uuid(); // Generate UUID
    $invoice->item_id = $this->item_id;
    $invoice->save();

    return $this->redirect('/invoices', navigate: true);
}
}; ?>






<div class="max-w-sm mx-auto">
    <form wire:submit="store">
        <flux:input wire:model="name" label="{{ __('Name') }}" type="text" name="name" required />
        <flux:select class="mt-2" wire:model="item_id" placeholder="Choose Item...">

        @foreach ($this->items as $item)
        <flux:select.option  value="{{$item->id}}">{{$item->name}}</flux:select.option>

        @endforeach

        </flux:select>


        <flux:button variant="primary" class="mt-2" type="submit">Save</flux:button>
    </form>
</div>
