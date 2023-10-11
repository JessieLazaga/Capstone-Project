<div>
    <div class="card card-default">
        <div class="card-header card-header-border-bottom">
            <h2>Update Product</h2>
        </div>
        <div class="card-body">
            
            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
                <div class="form-group">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" id="name" aria-describedby="emailHelp" value="{{ $product->name }}" wire:model="product.name">
                    @error('product.name')
                        <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="barcode" class="form-label">Barcode</label>
                    <input wire:model="product.barcode" type="text" name="barcode" class="form-control" id="barcode" aria-describedby="emailHelp" value="{{ $product->barcode }}">
                    @error('product.barcode')
                        <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="manufacturer_id">Select Manufacturer</label>
                        </div>
                        <select class="custom-select" id="manufacturer_id" name="manufacturer_id">
                            @foreach($manufacturers as $manufacturer)
                                <option value="{{$manufacturer->id}}">{{$manufacturer->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="validationDefaultUsername2">Price</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">₱</span>
                        </div>
                        <input wire:model="product.price" type="text" name="price" class="form-control" aria-label="Amount (to the nearest dollar)" value="{{ old('price', $product->price) }}">
                        <div class="input-group-append">
                            <span class="input-group-text">.00</span>
                        </div>
                        
                    </div>
                    @error('product.price')
                        <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input wire:model.lazy="product.quantity" type="text" name="quantity" class="form-control" id="quantity" aria-describedby="emailHelp" value="{{ old('quantity', $product->quantity) }}">
                    @error('product.quantity')
                        <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input wire:model.lazy="product.image" type="file" name="image" class="form-control-file" id="image">
                    @error('product.image')
                        <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary" 
                @if($errors->any())
                disabled
                @endif
                wire:loading.attr="disabled">Update</button>
            </form>
        </div>
    </div>
</div>
