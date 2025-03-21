<?php

use Livewire\Volt\Component;
use App\Models\Item;

new class extends Component {
    public Item $item;

    public function mount($id)
    {
        $this->item = Item::with('saleInformation', 'purchaseInformation')->findOrFail($id);
    }
}; ?>

<div>
    <div class="p-6  shadow-md rounded-lg">
        <h1 class="text-2xl font-bold mb-4">Item Details</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="font-semibold">ID:</p>
                <p>{{ $item->id }}</p>
            </div>
            <div>
                <p class="font-semibold">Name:</p>
                <p>{{ $item->name }}</p>
            </div>
            <div>
                <p class="font-semibold">SKU:</p>
                <p>{{ $item->sku }}</p>
            </div>
            <div>
                <p class="font-semibold">Type:</p>
                <p>{{ $item->type }}</p>
            </div>
            <div>
                <p class="font-semibold">Unit:</p>
                <p>{{ $item->unit }}</p>
            </div>
            <div>
                <p class="font-semibold">Returnable:</p>
                <p>{{ $item->returnable ? 'Yes' : 'No' }}</p>
            </div>
            <div>
                <p class="font-semibold">Brand:</p>
                <p>{{ $item->brand }}</p>
            </div>
            <div>
                <p class="font-semibold">Manufacturer:</p>
                <p>{{ $item->manufacturer }}</p>
            </div>
            <div>
                <p class="font-semibold">MPN:</p>
                <p>{{ $item->mpn }}</p>
            </div>
            <div>
                <p class="font-semibold">ISBN:</p>
                <p>{{ $item->isbn }}</p>
            </div>
            <div>
                <p class="font-semibold">UPC:</p>
                <p>{{ $item->upc }}</p>
            </div>
            <div>
                <p class="font-semibold">EAN:</p>
                <p>{{ $item->ean }}</p>
            </div>
            <div>
                <p class="font-semibold">Dimensions (L x W x H):</p>
                <p>{{ $item->length }} x {{ $item->width }} x {{ $item->height }}</p>
            </div>

            <div>
                <p class="font-semibold">Images:</p>
                <p>
                <div class="flex gap-2">
                    @foreach (json_decode($item->images) as $imagePath)
                        <a href="{{ asset('storage/' . $imagePath) }}" data-fancybox="gallery-{{ $item->id }}"
                            data-caption="{{ $item->name }}">
                            <img src="{{ asset('storage/' . $imagePath) }}" alt="Saved Image"
                                class="w-24 h-24 object-cover rounded-lg hover:opacity-75 transition-opacity">
                        </a>
                    @endforeach
                </div>
                </p>
            </div>
            <div>
                <p class="font-semibold">Created At:</p>
                <p>{{ $item->created_at }}</p>
            </div>
            <div>
                <p class="font-semibold">Updated At:</p>
                <p>{{ $item->updated_at }}</p>
            </div>
        </div>
        <br>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="">
        <h1 class="text-2xl font-bold mb-4">Sale Information</h1>

            </div>
            <div class="">
                <h1 class="text-2xl font-bold mb-4">Purchase Information</h1>

                    </div>
            <div>
                <p class="font-semibold">Selling Price:</p>
                <p>{{ $item->saleInformation?->selling_price }} MMK</p>
            </div>
            <div>
                <p class="font-semibold">Cost Price:</p>
                <p>{{ $item->purchaseInformation?->cost_price }} MMK</p>
            </div>

            <div>
                <p class="font-semibold">Sale Tax:</p>
                <p>{{ $item->saleInformation?->sale_tax }} %</p>
            </div>
            <div>
                <p class="font-semibold">Purchase Tax:</p>
                <p>{{ $item->purchaseInformation?->purchase_tax }} %</p>
            </div>

            <div>
                <p class="font-semibold">Sale Description:</p>
                <p>{{ $item->saleInformation?->sale_description }}</p>
            </div>

            <div>
                <p class="font-semibold">Purchase Description:</p>
                <p>{{ $item->purchaseInformation?->purchase_description }}</p>
            </div>
            <div class="">

            </div>
            <div>
                <p class="font-semibold">Preferred Vendor:</p>
                <p>{{ $item->purchaseInformation?->preferred_vendor }}</p>
            </div>
        </div>
        <div class="mt-6">
            <a href="/items" class="text-blue-500 hover:underline" wire:navigate>Back to List</a>
        </div>
    </div>
</div>

@script
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Bind Fancybox on initial load
        Fancybox.bind("[data-fancybox]", {});

        // Reinitialize Fancybox whenever Livewire updates the DOM
        Livewire.hook('message.processed', () => {
            Fancybox.bind("[data-fancybox]", {});
        });
    });
</script>
@endscript
