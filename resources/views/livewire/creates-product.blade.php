<div>
    <div class="card card-default">
        <div class="card-header card-header-border-bottom">
            <h2>Add Product</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1" class="form-label">Product Name</label>
                    <input type="text" wire:model="product.name" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('name') }}">
                    @error('product.name')
                        <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1" class="form-label">Barcode</label>
                    <input type="text" wire:model="product.barcode" name="barcode" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('barcode') }}">
                    @error('product.barcode')
                        <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="validationDefaultUsername2">Price</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">₱</span>
                        </div>
                        <input type="text" wire:model="product.price" name="price" class="form-control" aria-label="Amount (to the nearest dollar)" value="{{ old('price') }}">
                        <div class="input-group-append">
                            <span class="input-group-text">.00</span>
                        </div>
                        
                    </div>
                    @error('product.price')
                        <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1" class="form-label">Quantity</label>
                    <input type="text" wire:model="product.quantity" name="quantity" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('quantity') }}">
                    @error('product.quantity')
                        <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="exampleFormControlFile1">Image</label>
                    <input type="file" wire:model="product.image" name="image" class="form-control-file" id="exampleFormControlFile1" value="{{ old('image') }}">
                    @error('product.image')
                        <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Select Manufacturer</label>
                        </div>
                        <select name="manufacturer" class="custom-select" id="inputGroupSelect01">
                            @foreach($manufacturers as $manufacturer)
                                <option wire:model="product.manufacturer" value="{{$manufacturer->id}}">{{$manufacturer->name}}</option>
                            @endforeach
                        </select>
                        @error('product.manufacturer')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <button type="submit" 
                class="btn btn-primary"
                @if($errors->any())
                    disabled
                @endif
                    wire:loading.attr="disabled">Create</button>
            </form>
        </div>
    </div>
</div>
<script>
  // Get the product name textbox and all the other textboxes and inputs
  const productNameTextbox = document.querySelector('input[name="name"]');
  const otherTextboxesAndInputs = document.querySelectorAll('input:not([name="name"]), select, button');

  // Bind a change event listener to the product name textbox
  productNameTextbox.addEventListener('change', function() {
    // If the product name textbox has a value, enable the other textboxes and inputs
    if (this.value) {
      otherTextboxesAndInputs.forEach(function(element) {
        element.disabled = false;
      });
    } else { // Otherwise, disable the other textboxes and inputs
      otherTextboxesAndInputs.forEach(function(element) {
        element.disabled = true;
      });
    }
  });

  // Initially, disable the other textboxes and inputs
  otherTextboxesAndInputs.forEach(function(element) {
    element.disabled = true;
  });
</script>