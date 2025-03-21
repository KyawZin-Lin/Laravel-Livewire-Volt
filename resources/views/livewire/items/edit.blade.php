<?php

use Livewire\Volt\Component;
use App\Models\Item;
use App\Models\SaleInformation;
use App\Models\PurchaseInformation;



use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $images = [];
    public $new_images = []; // Newly uploaded images
    public $deleted_images = []; // Track deleted images

    public $item_id;
    public $name;
    public $sku;
    public $unit;
    public $brand;
    public $type = [];
    public $dimensions; // Single field for dimensions (L x W x H)
    public $manufacturer;
    public $mpn;
    public $isbn;
    public $upc;
    public $ean;
    public $selling_price;
    public $cost_price;
    public $sale_description;
    public $purchase_description;
    public $sale_tax;
    public $purchase_tax;
    public $preferred_vendor;



    public function mount($id)
    {
        $item = Item::with('saleInformation', 'purchaseInformation')->findOrFail($id);
        $this->item_id = $item->id;

        $this->name = $item->name;
        $this->sku = $item->sku;
        $this->unit = $item->unit;
        $this->brand = $item->brand;
        $this->type = json_decode($item->type);
        $this->dimensions = $item->length . ' x ' . $item->width . ' x ' . $item->height;
        $this->manufacturer = $item->manufacturer;
        $this->mpn = $item->mpn;
        $this->isbn = $item->isbn;
        $this->upc = $item->upc;
        $this->ean = $item->ean;
        $this->images = json_decode($item->images, true) ?? [];
        $this->selling_price = $item->saleInformation->selling_price;
        $this->cost_price = $item->purchaseInformation->cost_price;
        $this->sale_description = $item->saleInformation->sale_description;
        $this->purchase_description = $item->purchaseInformation->purchase_description;
        $this->sale_tax = $item->saleInformation->sale_tax;
        $this->purchase_tax = $item->purchaseInformation->purchase_tax;
        $this->preferred_vendor = $item->purchaseInformation->preferred_vendor;

        // dd($this->images);
    }

    public function deleteImage($index)
    {
        // Add the deleted image to the deleted_images array
        $this->deleted_images[] = $this->images[$index];

        // Remove the image from the images array
        unset($this->images[$index]);

        // Reindex the array
        $this->images = array_values($this->images);
    }

    public function update()
    {
        // Validate dimensions format (L x W x H)
        $dimensionsArray = explode('x', $this->dimensions);
        if (count($dimensionsArray) !== 3) {
            session()->flash('error', 'Dimensions must be in the format "L x W x H".');
            return;
        }

        $item = Item::findOrFail($this->item_id);
        $item->name = $this->name;
        $item->sku = $this->sku;
        $item->unit = $this->unit;
        $item->brand = $this->brand;
        $item->type = json_encode($this->type);
        $item->length = trim($dimensionsArray[0]); // Extract length
        $item->width = trim($dimensionsArray[1]); // Extract width
        $item->height = trim($dimensionsArray[2]); // Extract height
        $item->manufacturer = $this->manufacturer;
        $item->mpn = $this->mpn;
        $item->isbn = $this->isbn;
        $item->upc = $this->upc;
        $item->ean = $this->ean;

         // Handle new images
         $newImagePaths = [];
        if ($this->new_images) {
            foreach ($this->new_images as $image) {
                $imagePath = $image->storeAs('item-images', $image->getClientOriginalName(), 'public');
                $newImagePaths[] = $imagePath;
            }
        }

        // Combine remaining existing images with new images
        $item->images = json_encode(array_merge($this->images, $newImagePaths));

        $item->save();


        if(isset($item) && $this->selling_price){
            $item->saleInformation->update([
                'item_id' => $item->id,
                'selling_price' => $this->selling_price,
                'sale_description' => $this->sale_description,
                'sale_tax' => $this->sale_tax
            ]);

        }

        if(isset($item) && $this->cost_price){
            $item->purchaseInformation->update([
                'item_id' => $item->id,
                'cost_price' => $this->cost_price,
                'purchase_description' => $this->purchase_description,
                'purchase_tax' => $this->purchase_tax,
                'preferred_vendor' => $this->preferred_vendor,

            ]);

        }
        // Reset inputs after saving
        $this->reset(['name', 'sku', 'unit', 'brand', 'type', 'images', 'dimensions', 'manufacturer', 'mpn', 'isbn', 'upc', 'ean']);
        return $this->redirect('/items', navigate: true);
    }
};
?>


<div>
    <h1>Edit Item</h1>
    <br>
    <form wire:submit="update">
        <div class="flex flex-col space-y-6">

            <!-- Type -->
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

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                <input type="text" wire:model="name" id="name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Enter name" required />
            </div>

            <!-- SKU -->
            <div class="mb-6">
                <label for="sku" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SKU</label>
                <input type="text" wire:model="sku" id="sku"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Enter SKU" required />
            </div>

            <!-- Unit and Brand -->
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

            <!-- Dimensions (L x W x H) -->
            <div class="mb-6">
                <label for="dimensions" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dimensions
                    (L x W x H)</label>
                <input type="text" wire:model="dimensions" id="dimensions"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Enter dimensions (e.g., 10 x 5 x 2)" required />
            </div>

            <!-- Manufacturer, MPN, ISBN -->
            <div class="flex space-x-4 mb-6">
                <div class="flex-1">
                    <label for="manufacturer"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Manufacturer</label>
                    <input type="text" wire:model="manufacturer" id="manufacturer"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter manufacturer" required />
                </div>
                <div class="flex-1">
                    <label for="mpn"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">MPN</label>
                    <input type="text" wire:model="mpn" id="mpn"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter MPN" required />
                </div>
                <div class="flex-1">
                    <label for="isbn"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ISBN</label>
                    <input type="text" wire:model="isbn" id="isbn"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter ISBN" required />
                </div>
            </div>

            <!-- UPC and EAN -->
            <div class="flex space-x-4 mb-6">
                <div class="flex-1">
                    <label for="upc"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">UPC</label>
                    <input type="text" wire:model="upc" id="upc"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter UPC" required />
                </div>
                <div class="flex-1">
                    <label for="ean"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">EAN</label>
                    <input type="text" wire:model="ean" id="ean"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter EAN" required />
                </div>
            </div>

            <!-- Images -->
            {{-- <div class="mb-6">
                <label for="images"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Images</label>
                <input type="file" wire:model="images" id="images" multiple
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    accept="image/*" required />

                <!-- Display uploaded images -->
                @if ($images)
                    <div class="mt-4">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Uploaded Images:</h3>
                        <div class="flex flex-wrap gap-4 mt-2">
                            @foreach ($images as $image)
                                <img src="{{asset('storage/' . $image )}}" alt="Uploaded Image"
                                    class="w-24 h-24 object-cover rounded-lg">
                            @endforeach
                        </div>
                    </div>
                @endif
            </div> --}}

                <!-- Display existing images with delete buttons -->
        <div class="mb-6">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Existing Images</label>
            <div class="flex flex-wrap gap-4">
                @foreach ($images as $index => $image)
                    <div class="relative">
                        <img src="{{ asset('storage/'. $image) }}" alt="Existing Image" class="w-24 h-24 object-cover rounded-lg">
                        <button type="button" wire:click="deleteImage({{ $index }})" class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1">
                            &times;
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Upload new images -->
        <div class="mb-6">
            <label for="new_images" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload New Images</label>
            <input type="file" wire:model="new_images" id="new_images" multiple
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                accept="image/*" />

            <!-- Display newly uploaded images -->
            @if ($new_images)
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Newly Uploaded Images:</h3>
                    <div class="flex flex-wrap gap-4 mt-2">
                        @foreach ($new_images as $image)
                            <img src="{{ $image->temporaryUrl() }}" alt="Newly Uploaded Image" class="w-24 h-24 object-cover rounded-lg">
                        @endforeach
                    </div>
                </div>
            @endif
        </div>


            <div class="flex space-x-4 mb-6">
                <div class="flex-1">
                    <h1>Sale Information</h1>

                </div>
                <div class="flex-1">
                    <h1>Purchase Information</h1>

                </div>
            </div>



            <div class="flex space-x-4 mb-6">
                <div class="flex-1">
                    <label for="selling_price"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selling Price</label>
                    <input type="number" wire:model="selling_price" id="selling_price"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter Selling Price" required />
                </div>
                <div class="flex-1">
                    <label for="cost_price"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cost Price</label>
                <input type="number" wire:model="cost_price" id="cost_price"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Enter Selling Price" required />
                </div>

            </div>
            <div class="flex space-x-4 mb-6">
                <div class="flex-1">
                    <label for="sale_description"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sale Description</label>

                    <textarea
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        name="" wire:model="sale_description" id="" cols="30" rows="10"></textarea>

                </div>
                <div class="flex-1">
                    <label for="purchase_description"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Purchase Description</label>

                <textarea
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    name="" id="" wire:model="purchase_description" cols="30" rows="10"></textarea>
                </div>

            </div>

            <div class="flex space-x-4 mb-6">
                <div class="flex-1">
                    <label for="sale_tax"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tax</label>
                    <input type="number" wire:model="sale_tax" id="sale_tax"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter Sale Tax" required />
                </div>
                <div class="flex-1">
                    <label for="purchase_tax"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tax</label>
                    <input type="number" wire:model="purchase_tax" id="purchase_tax"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter Purchase Tax" required />
                </div>

            </div>

            <div class="flex space-x-4 mb-6">
                <div class="flex-1">

                </div>
                <div class="flex-1">
                    <label for="preferred_vendor"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Preferred Vendor
                    </label>
                    <input type="text" wire:model="preferred_vendor" id="preferred_vendor"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter Purchase Tax" required />
                </div>

            </div>


        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
    </form>
</div>
