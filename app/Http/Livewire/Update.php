<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Update extends Component
{
    public $product;
    public $manufacturers;

    public function rules()
    {
        $productId = $this->product->id;
        return [
            'product.name' => 'required|max:30|unique:products, name,'.$productId,
            'product.image' => 'nullable|mimes:jpg,jpeg,png',
            'product.quantity' => 'required|integer',
            'product.barcode' => 'required|max:12|unique:products,barcode,'.$productId,
            'product.price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ];
   }
   public function updated($propertyName)
    {
        $productId = $this->product->id;
        $this->validateOnly($propertyName, [
            'product.name' => 'required|max:30|unique:products,name,'.$productId,
            'product.image' => 'nullable|mimes:jpg,jpeg,png',
            'product.quantity' => 'required|integer',
            'product.barcode' => 'required|max:12|unique:products,barcode,'.$productId,
            'product.price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        
    }
    public function render()
    {
        return view('livewire.update')->with('manufacturers', $this->manufacturers);
    }
}
