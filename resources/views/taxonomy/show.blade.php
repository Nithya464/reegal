<div class="row">
  <div class="col-md-10 col-md-offset-1 col-xs-12">
    <div class="table-responsive">
      <table class="table table-condensed bg-gray" id="SubCategoryTable">
        <tr>
          <th>Subcategory ID</th>
          <th>Subcategory Name</th>
          <th>Sequence No</th>
          <th>Show On Website</th>
          <th>Status</th>
          <th>Description</th>
          <th>Action</th>
          {{-- <th>@lang('lang_v1.position')</th> --}}
        </tr>
        @if(!empty($details[0]))
          @foreach( $details as $detail )
            <tr>
              <td>{{ $detail->short_code}}</td>
              <td>
                {{ $detail->name}}
              </td>
              <td>
                {{ $detail->website_sequence_no}}
              </td>
              <td>
                @if($detail->show_on_website=='1')
                  <span class="badge rounded-pill bg-success" style="background-color: #198754 !important;">Yes</span>
                @else
                  <span class="badge rounded-pill bg-danger" style="background-color: #dc3545 !important;">NO</span>
                @endif
              </td>
              <td>
                @if($detail->status=='1')
                  <span class="badge rounded-pill bg-success" style="background-color: #198754 !important;">Active</span>
                @else
                  <span class="badge rounded-pill bg-danger" style="background-color: #dc3545 !important;">In Active</span>
                @endif
              </td>
              <td>
                {{ $detail->description }}
              </td>
              <td>
                @if($can_edit)
                <button data-href="{{action([\App\Http\Controllers\TaxonomyController::class, 'edit'], [$detail->id])}}?type=product" class="btn btn-xs btn-primary edit_category_button" title="Edit"><i class="glyphicon glyphicon-edit" style="padding: 2px 1px 5px;"></i></button>
                @endif
                @if($can_delete)
                <button data-href="{{action([\App\Http\Controllers\TaxonomyController::class, 'destroy'], [$detail->id])}}" class="btn btn-xs btn-danger delete_category_button" title="Delete"><i class="glyphicon glyphicon-trash" style="padding: 2px 1px 5px;"></i></button>
                @endif
              </td>
              
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="4" class="text-center">
              -
            </td>
          </tr>
        @endif
        
      </table>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    $('#SubCategoryTable').DataTable();
  });
</script>