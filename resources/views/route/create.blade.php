<div class="modal-dialog" role="document">
    <div class="modal-content">
  
      {!! Form::open(['url' => action([\App\Http\Controllers\RouteController::class, 'store']), 'method' => 'post', 'id' => $quick_add ? 'quick_add_route_form' : 'route_add_form' ]) !!}
  
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'Add Route' )</h4>
      </div>
  
      <div class="modal-body">
        <div class="form-group">
          {!! Form::label('route_id', __( 'Route ID' ) . ':') !!}
            {!! Form::text('route_id', null, ['class' => 'form-control','placeholder' => __( 'Route ID' ), 'readonly']); !!}
        </div>

        <div class="form-group">
            {!! Form::label('name', __( 'Route Name' ) . ':*') !!}
              {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'Route Name' ) ]); !!}
        </div>

        <div class="form-group">
          {!! Form::label('sales_person_id', __( 'Sales Person' ) . ':') !!}
            {!! Form::select('sales_person_id', $users,null, ['class' => 'form-control']); !!}
        </div>

        <div class="form-group">
          {!! Form::label('status', __( 'Status' ) . ':') !!}
            {!! Form::select('status', config('global.status_options'),1, ['class' => 'form-control']); !!}
        </div>
        
        <div class="form-group">
          {!! Form::label('customer_ids', __('Customer') . ':*' ) !!}
          <div class="input-group">
              <span class="input-group-addon">
                  <i class="fa fa-user"></i>
              </span>
              {!! Form::select('customer_ids[]', $customers ?? [], null , ['class' => 'form-control select2', 'id' => 'customer_ids', 'multiple', 'required', 'style' => 'width: 100%;']); !!}
          </div>
        </div>

      </div>
  
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>
  
      {!! Form::close() !!}
  
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->