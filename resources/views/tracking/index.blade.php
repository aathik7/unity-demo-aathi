<x-app-layout>
@include('flash-message')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           {{__('Inventory')}} 
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="row">
                <div class="block col-md-1 mb-4 flex items-center justify-end">
                    <a href="{{ route('tracking.edit') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                    {{__('Update Stock')}} </a>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-8 min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 w-full">
                                <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs uppercase tracking-wider">
                                        {{__('Name')}}
                                    </th>
                                    <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs uppercase tracking-wider">
                                        {{__('Quantity')}}
                                    </th>
                                    <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs uppercase tracking-wider">
                                        {{__('Status')}}
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($inventoryItems as $item)
                                    <tr @if($item['quantity'] < 5) style="background-color: coral;" @endif>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item->item_name }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item->quantity }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item->active_flag == 1 ? 'Active' : 'Inactive' }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                </table>
                               
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <form action="{{ route('inventory.destroy', '$item->id') }}" method="post">
                                        <input id="id" name="itemId" type="hidden">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span hidden="true close-btn">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                            @csrf
                                            @method('DELETE')
                                                <p>Are you sure you want to delete this company?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button"class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-500 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150 close-btn" 
                                                 data-dismiss="modal">Close</button>
                                                <button type="submit" class="inline-flex items-center px-4 py-2 mr-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150"> Delete</button>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                </div>                              
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    window.onload = function() {
        showStockAlert();
    };
    function showStockAlert() {
        $.ajax({
            type: "GET",
            url: "/inventory/tracking/check-empty-items",
            data: '_token=<?php echo csrf_token() ?>',
            
            success: function(data) {
                if (data > 0) {
                    alert('Some of the items Quantity is Low!');
                }
            }
        });
    }
</script>


