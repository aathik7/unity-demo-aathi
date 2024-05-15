<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           {{ $title }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="POST" action="{{ route('tracking.update') }}" enctype="multipart/form-data"  autocomplete="off">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-2 bg-white sm:p-6">
                            <label for="roles" class="block font-medium text-sm text-gray-700">{{__('Item')}}</label>
                            <select name="item_id" id="item_id" class="form-multiselect block rounded-md shadow-sm mt-1 block w-full" onChange='getQuantity(this.value)' required>
                                <option value="">Select Item</option>
                                     @foreach($stocks as $key => $stock)
                                        <option value="{{$stock['id']}}">{{$stock['item_name']}}</option>
                                    @endforeach
                            </select>
                            @error('item_id')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="px-4 py-2 bg-white sm:p-6">
                            <label for="quantity" class="block font-medium text-sm text-gray-700">{{__('Quantity')}}</label>
                            <input type="text" name="quantity" id="quantity" value="" class="form-input rounded-md shadow-sm mt-1 block w-full" required />
                            @error('quantity')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            
                            <button type="submit" class="inline-flex items-center px-4 py-2 mr-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                {{__('Update')}}
                            </button>
                            <button type="button" x-data @click="cancel()" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-500 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150 cancelBtn">
                                {{__('Cancel')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
  function cancel() {
    window.location.href = "{{route('inventory.index')}}";
  }

  document.addEventListener('alpine:init', () => {
    Alpine.data('cancel', () => ({
      cancel
    }));
  });

    function getQuantity(itemId) {
        $.ajax({
            type: "GET",
            url: "/inventory/tracking/get-quantity",
            data: '_token=<?php echo csrf_token() ?>'+'&id='+itemId,
            
            success: function(data) {
                document.getElementById("quantity").value = data;
            }
        });
    }
</script>