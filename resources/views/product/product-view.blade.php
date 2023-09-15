<div class="modal-dialog modal-xl" role="document">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		      <h4 class="modal-title" id="modalTitle">{{$product->name}}</h4>
	    </div>
	    <div class="modal-body">
      		<div class="row">
      			<div class="col-sm-9">
	      			<div class="col-sm-6 invoice-col">
	      				<b>@lang('product.sku'):</b>
						{{$product->sku}}<br>
                        <b>@lang('product.category'): </b>
						{{$product->category->name ?? '--' }}<br>
                        <b>@lang('product.sub_category'): </b>
						{{$product->sub_category->name ?? '--' }}<br>
                        <b>@lang('product.brand'): </b>
						{{$product->brand->name ?? '--' }}<br>
						<b>Is Apply ML Quantity: </b>
						@if($product->is_ml_qty==1)
                            <span class='badge rounded-pill bg-success'>Yes</span><br>
                        @else
                           <span class='badge rounded-pill bg-danger'>No</span><br>
                        @endif
						<b>Is Apply Weight (OZ): </b>
						@if($product->is_weight==1)
                            <span class='badge rounded-pill bg-success'>Yes</span><br>
                        @else
                           <span class='badge rounded-pill bg-danger'>No</span><br>
                        @endif
						<b>Stock: </b>
						{{$stock}}<br>
	      			</div>

	      			<div class="col-sm-6 invoice-col">
						<b>Reorder Mark: </b>
						{{$product->re_order_mark ?? '--' }}<br>
						<b>Status: </b>
                        @if($product->is_inactive==0)
                            <span class='badge rounded-pill bg-success'>Active</span><br>
                        @else
                           <span class='badge rounded-pill bg-danger'>Inactive</span><br>
                        @endif
                        <b>SRP: </b>
						${{$product->srp ?? '--' }}<br>
                        <b>Commission Code: </b>
                        {{$product->commission_per}}%
						<br>
						<b>ML Quantity: </b>
						{{$product->ml_qty ?? '--' }}<br>
                        <b>Weight (Oz): </b>
						
						{{$product->weight ?? '--' }}
						<br>
					</div>
					
	      			

	      			<div class="clearfix"></div>
	      			<br>
      				<div class="col-sm-12">
      					{!! $product->product_description !!}
      				</div>
	      		</div>
      			<div class="col-sm-3 col-md-3 invoice-col">
      				<div class="thumbnail">
      					<img src="{{$product->image_url}}" alt="Product image">
      				</div>
      			</div>
      		</div>
			  
			  <div class="row text-center">
				
				<div class="col-md-8">
					<div class="table-responsive">
						<table class="table bg-gray">
							<tr class="bg-green">
								<th>Location Wise Status</th>
							</tr>
							@foreach($product->product_locations as $location)
							<tr>
								<td>{{$location->name}}</td>
							</tr>
							@endforeach
						</table>
					</div>
				</div>
				<div class="col-md-4">
				</div>
			</div>
      		
      	</div>
      	<div class="modal-footer">
      		<button type="button" class="btn btn-primary no-print" 
	        aria-label="Print" 
	          onclick="$(this).closest('div.modal').printThis();">
	        <i class="fa fa-print"></i> @lang( 'messages.print' )
	      </button>
	      	<button type="button" class="btn btn-default no-print" data-dismiss="modal">@lang( 'messages.close' )</button>
	    </div>
	</div>
</div>
