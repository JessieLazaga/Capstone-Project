<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CreatesProduct extends Component
{
    public $manufacturers;
    public $product;
    public function render()
    {
        return view('livewire.creates-product')->with('manufacturers', $this->manufacturers);
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'product.name' => 'unique:products,name|max:80',
            'product.image' => 'nullable|mimes:jpg,jpeg,png',
            'product.manufacturer' => 'required',
            'product.quantity' => 'required|max:11',
            'product.barcode' => 'required|unique:products,barcode|max:12',
            'product.price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
    }
}
