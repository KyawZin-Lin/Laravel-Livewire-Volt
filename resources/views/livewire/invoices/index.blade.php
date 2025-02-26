<?php

use App\Models\Invoice;
use Livewire\Volt\Component;
use Livewire\WithPagination;
new class extends Component {
    use WithPagination;
    // public $invoices ;
    public $search = '';

    public function with(): array
    {
        $query = Invoice::query();

        if (!empty($this->search)) {
            $query->where('invoice_id', 'LIKE', '%' . $this->search . '%')->orWhereHas('item', function ($query) {
                $query->where('name', 'LIKE', '%' . $this->search . '%');
            });
        }

        return [
            'invoices' => $query->paginate(2),
        ];
    }

    public function create()
    {
        return $this->redirect('/invoices/create', navigate: true);
    }

    public function delete($id)
    {
        $invoice = Invoice::find($id); // Retrieve the instance
        if ($invoice) {
            $invoice->delete(); // Call delete() on the instance
        }
        $this->resetPage(); // Reset to page 1
    }

    public function search()
    {
        $this->invoices = Invoice::where('invoice_id', 'LIKE', '%' . $this->search . '%')
            ->orWhereHas('item', function ($query) {
                $query->where('name', 'LIKE', '%' . $this->search . '%');
            })
            ->get();
    }

    public function updatedSearch()
    {
        $this->search();
    }
}; ?>

{{-- <div class="relative overflow-x-auto">
    <div class="flex m-1">
        <a href="/invoices/create" class="w-1/2" wire:navigate> <flux:button>Create</flux:button></a>
        <flux:input class="w-2/3" wire:model.live="search" icon="magnifying-glass" placeholder="Search Invoices" />
    </div>

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">ID</th>
                <th scope="col" class="px-6 py-3">Name</th>
                <th scope="col" class="px-6 py-3">Invoice</th>
                <th scope="col" class="px-6 py-3">Item</th>
                <th scope="col" class="px-6 py-3">Created At</th>
                <th scope="col" class="px-6 py-3">Updated At</th>
                <th scope="col" class="px-6 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $invoice)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $invoice->id }}
                    </th>
                    <td class="px-6 py-4">{{ $invoice->user->name }}</td>
                    <td class="px-6 py-4">{{ $invoice->invoice_id }}</td>
                    <td class="px-6 py-4">{{ $invoice->item->name }}</td>


                    <td class="px-6 py-4">{{ $invoice->created_at }}</td>
                    <td class="px-6 py-4">{{ $invoice->updated_at }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="/invoices/{{ $invoice->id }}/edit" wire:navigate>
                                <flux:button variant="primary">Edit</flux:button>
                            </a>
                            <flux:button wire:confirm="Are you sure you want to delete this invoice?"
                                wire:click="delete({{ $invoice->id }})" variant="danger">Delete</flux:button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div> --}}

{{-- <div class="relative overflow-x-auto shadow-lg rounded-lg">
    <div class="flex items-center justify-between p-4 bg-white rounded-t-lg border-b">
        <flux:input class="w-2/3" wire:model.live="search" icon="magnifying-glass" placeholder="Search Invoices..." />
        <a href="/invoices/create" wire:navigate>
            <flux:button variant="primary" class="ml-2">+ Create Invoice</flux:button>
        </a>
    </div>

    <table class="w-full text-sm text-left border-collapse">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
            <tr class="border-b">
                <th class="px-6 py-3">#</th>
                <th class="px-6 py-3">User</th>
                <th class="px-6 py-3">Invoice ID</th>
                <th class="px-6 py-3">Item</th>
                <th class="px-6 py-3">Created</th>
                <th class="px-6 py-3">Updated</th>
                <th class="px-6 py-3 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoices as $index => $invoice)
                <tr class="border-b {{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                    <td class="px-6 py-4 font-medium">{{ $invoice->id }}</td>
                    <td class="px-6 py-4">{{ $invoice->user->name }}</td>
                    <td class="px-6 py-4">{{ $invoice->invoice_id }}</td>
                    <td class="px-6 py-4">{{ $invoice->item->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $invoice->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $invoice->updated_at->diffForHumans() }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <a href="/invoices/{{ $invoice->id }}/edit" wire:navigate>
                                <flux:button size="sm" variant="primary">Edit</flux:button>
                            </a>
                            <flux:button size="sm" variant="danger"
                                wire:click="delete({{ $invoice->id }})"
                                wire:confirm="Are you sure you want to delete this invoice?">
                                Delete
                            </flux:button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">No invoices found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div> --}}

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="flex justify-between items-center mb-4">
        <a href="/invoices/create" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" wire:navigate>
            Create Invoice
        </a>
        <div class="w-1/3">
            <input type="text" wire:model.live="search"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Search Invoices...">
        </div>
    </div>

    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">ID</th>
                <th scope="col" class="px-6 py-3">Name</th>
                <th scope="col" class="px-6 py-3">Invoice</th>
                <th scope="col" class="px-6 py-3">Item</th>
                <th scope="col" class="px-6 py-3">Created At</th>
                <th scope="col" class="px-6 py-3">Updated At</th>
                <th scope="col" class="px-6 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoices as $invoice)
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">{{ $invoice->id }}</td>
                    <td class="px-6 py-4">{{ $invoice->user->name }}</td>
                    <td class="px-6 py-4">{{ $invoice->invoice_id }}</td>
                    <td class="px-6 py-4">{{ $invoice->item->name }}</td>
                    <td class="px-6 py-4">{{ $invoice->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-6 py-4">{{ $invoice->updated_at->format('Y-m-d H:i') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="/invoices/{{ $invoice->id }}/edit" wire:navigate>
                                <button
                                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Edit</button>
                            </a>
                            <button wire:confirm="Are you sure you want to delete this invoice?"
                                wire:click="delete({{ $invoice->id }})"
                                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center">No invoices found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $invoices->links() }}
    </div>
</div>
