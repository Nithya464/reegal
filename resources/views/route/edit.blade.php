<div class="modal-dialog" role="document">
    <div class="modal-content">
       {!! Form::open(['url' => action([\App\Http\Controllers\RouteController::class, 'update'], [$route->id]), 'method' => 'PUT', 'id' => 'route_edit_form' ]) !!}
  
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'Edit Route' )</h4>
      </div>
  
      <div class="modal-body">
        <div class="form-group">
          {!! Form::label('route_id', __( 'Route Id' ) . ':*') !!}
            {!! Form::text('route_id', $route->route_id, ['class' => 'form-control', 'required', 'placeholder' => __( 'Route Id' ), 'readonly']); !!}
        </div>

        <div class="form-group">
            {!! Form::label('name', __( 'Route Name' ) . ':*') !!}
            {!! Form::text('name', $route->name, ['class' => 'form-control', 'required', 'placeholder' => __( 'Route Name' )]); !!}
        </div>
          
        <div class="form-group">
            {!! Form::label('sales_person_id', __( 'Sales Person' ) . ':') !!}
            {!! Form::select('sales_person_id', $users, $route->sales_person_id, ['class' => 'form-control']); !!}
        </div>

        <div class="form-group">
            {!! Form::label('customer_ids', __('Customer') . ':*' ) !!}
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-user"></i>
                </span>
                {!! Form::select('customer_ids[]', $customers ?? [], $route->userHavingAccess->pluck('id') , ['class' => 'form-control select2', 'id' => 'customer_ids', 'multiple', 'required', 'style' => 'width: 100%;']); !!}
            </div>
        </div>

        <div class="form-group">
          {!! Form::label('status', __( 'Status' ) . ':') !!}
            {!! Form::select('status', config('global.status_dropdown'), $route->status, ['class' => 'form-control']); !!}
        </div>
  
      </div>
  
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>
  
      {!! Form::close() !!}
  
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->