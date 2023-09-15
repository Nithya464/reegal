<div class="modal-dialog" role="document">
  <div class="modal-content">

    {{-- {!! Form::open(['url' => action([\App\Http\Controllers\TaxonomyController::class, 'store']), 'method' => 'post', 'id' => 'category_add_form' ]) !!} --}}
    {!! Form::open(['url' => action([\App\Http\Controllers\TaxonomyController::class, 'store']), 'method' => 'post', 'id' => $quick_add ? 'quick_add_category_form' : 'category_add_form' ]) !!}
    
    
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'messages.add' )</h4>
    </div>

    <div class="modal-body">
      <input type="hidden" name="category_type" value="{{$category_type}}">
      @php
        $name_label = !empty($module_category_data['taxonomy_label']) ? $module_category_data['taxonomy_label'] : __( 'category.category_name' );
        $cat_code_enabled = isset($module_category_data['enable_taxonomy_code']) && !$module_category_data['enable_taxonomy_code'] ? false : true;

        $cat_code_label = !empty($module_category_data['taxonomy_code_label']) ? $module_category_data['taxonomy_code_label'] : __( 'category.code' );

        $enable_sub_category = isset($module_category_data['enable_sub_taxonomy']) && !$module_category_data['enable_sub_taxonomy'] ? false : true;

        $category_code_help_text = !empty($module_category_data['taxonomy_code_help_text']) ? $module_category_data['taxonomy_code_help_text'] : __('lang_v1.category_code_help');
      @endphp
      <div class="form-group">
        {!! Form::label('name', $name_label . ':*') !!}
          {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => $name_label]); !!}
      </div>
      @if($cat_code_enabled)
      <div class="form-group">
        {!! Form::label('short_code', $cat_code_label . ':') !!}
        {!! Form::text('short_code', null, ['class' => 'form-control', 'placeholder' => $cat_code_label]); !!}
        <p class="help-block">{!! $category_code_help_text !!}</p>
      </div>
      @endif
      <div class="form-group">
        {!! Form::label('description', __( 'lang_v1.description' ) . ':') !!}
        {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __( 'lang_v1.description'), 'rows' => 3]); !!}
      </div>
      <div class="form-group">
        {!! Form::label('website_sequence_no', __( 'Website Sequence No' ) . ':') !!}
        {!! Form::text('website_sequence_no', null, ['class' => 'form-control input_number', 'placeholder' => __( 'Enter Website Sequence No')]); !!}
      </div>
      @if(!$quick_add)
      <div class="form-group">
        {!! Form::label('status', __( 'Status' ) . ':') !!}
          {!! Form::select('status', config('global.status_dropdown'),null, ['class' => 'form-control']); !!}
      </div>
      @endif
      <div class="form-group">
        {!! Form::label('show_on_website', __( 'Show On Website' ) . ':') !!}
        {!! Form::checkbox('show_on_website', 1, false,[ 'class' => 'toggler']); !!} 
      </div>
      @if(!$quick_add)
      @if(!empty($parent_categories) && $enable_sub_category)
        <div class="form-group">
            <div class="checkbox">
              <label>
                 {!! Form::checkbox('add_as_sub_cat', 1, false,[ 'class' => 'toggler', 'data-toggle_id' => 'parent_cat_div' ]); !!} Add as sub-category
              </label>
            </div>
        </div>
        <div class="form-group hide" id="parent_cat_div">
          {!! Form::label('parent_id', __( 'category.select_parent_category' ) . ':') !!}
          {!! Form::select('parent_id', $parent_categories, null, ['class' => 'form-control']); !!}
        </div>
      @endif
      @endif

    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    {!! Form::close() !!}
  

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->