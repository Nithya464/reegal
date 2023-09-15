<div class="box {{$class ?? 'box-solid'}}" @if(!empty($id)) id="{{$id}}" @endif>
    @if(empty($header))
        @if(!empty($title) || !empty($tool))
        <div class="box-header">
            {!!$icon ?? '' !!}
            <h3 class="box-title">{{ $title ?? '' }}</h3>
            {!!$tool ?? ''!!}

            @if(!empty($is_memo))
                <div style="float: right;width: 25%;">
                    <div class="form-group">
                        {!! Form::label('sell_order_id',  __('Orders') . ':') !!}
                        {!! Form::select('sell_order_id', $orders, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __((!empty($orders))? 'Select Order For Credit Memo': 'Order not available for Credit Memo')]); !!}
                    </div>
                </div>
            @endif
        </div>
        @endif
    @else
        <div class="box-header">
            {!! $header !!}
        </div>
    @endif

    <div class="box-body">
        {{$slot}}
    </div>
    <!-- /.box-body -->
</div>