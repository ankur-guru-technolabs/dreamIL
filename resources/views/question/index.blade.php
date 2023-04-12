{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Data Table')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/flag-icon/css/flag-icon.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/jquery.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css"
  href="{{asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/select.dataTables.min.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/data-tables.css')}}">
@endsection

{{-- page content --}}
@section('content')
<div class="section section-data-tables">
  <div class="row">
    <div class="col s12">
      <div class="card">
        <div class="card-content">
          <h4 class="card-title">Questions List</h4>
          <div class="row">
            @if(Session::get('success'))
                <div class="alert  card-alert card green">
                    <div class="card-content white-text">
                        <p>{{Session::get('success')}}</p>
                    </div>
					  <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
				</div>	
            @endif
            <div class="col s12">
              <table id="page-length-option" class="display">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Category</th>
                    <th>Question</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th> </th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($questions as $question)
                  <tr>
                    <td>{{$question->id}}</td>
                    <td>@if(isset($categories[$question->cat_id])) {{$categories[$question->cat_id]}} @endif</td>
                    <td>{{$question->question}}</td>
                    <td>{{$question->created_at}}</td>
                    <td>{{$question->updated_at}}</td>
                    <td>						@if($question->is_default == "Yes")						    <a href="javascript:void(0);"  data-id="{{$question->id}}" data-cat-id="{{$question->cat_id}}" ><i class="material-icons" style="width:20px;">star</i></a>						@else								<a href="javascript:void(0);" class="change-star" data-id="{{$question->id}}" data-cat-id="{{$question->cat_id}}"><i class="material-icons" style="width:20px;">star</i></a>						@endif													<a href="{{ route('question.edit',$question->id)}}"><i class="material-icons">edit</i></a>
                      <a onclick="return confirm('Are you sure you want delete this?')" href="{{url('question/delete')}}/{{$question->id}}"><i class="material-icons">delete</i></a></td></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>
<style>
.change-star i{
	color:#808080 !important;
} 
</style>
<script src="{{asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/data-tables/js/dataTables.select.min.js')}}"></script>
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/data-tables.js')}}"></script><script>	$(document).ready(function(){		$(document).on('click','.change-star',function(){				var id = $(this).data('id');				var cat_id = $(this).data('cat-id');				jQuery.ajax({                        headers: {                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')                        },                        url:"{{url('question/set_default')}}",                        data:{id:id,cat_id:cat_id},                        type:'POST',                        success:function(data){								 location.reload();                                                  }                    });		});	});</script>
@endsection