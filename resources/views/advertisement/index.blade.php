{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Advertisements List')

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
          <h4 class="card-title">Advertisements List</h4>
          <div class="row">
            @if(Session::get('success'))
                <div class="alert card-alert card green">
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
                    
                    <th>Name</th>
                    <th>User</th>
                    <th>Category</th>
                    <th>Images</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Updated at</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($advertisements as $advertisement)
                  <tr>
                    <td>{{$advertisement['id']}}</td>
                    <td>@if(isset($advertisement['user']['first_name'])) {{$advertisement['user']['first_name']}} @endif</td>
                    <td>@if(isset($advertisement['advertisement_category']['category'])) {{$advertisement['advertisement_category']['category']}} @endif</td>
                    <td>@if(isset($advertisement['advertisement_images']))
                           @foreach($advertisement['advertisement_images'] as $image)
                              <img src="{{$image['image']}}" width="50px" height="50px">
                           @endforeach
                        @endif
                    </td>
                    <td>{{$advertisement['start_date']}}</td>
                    <td>{{$advertisement['end_date']}}</td>
                    <td>{{$advertisement['status']}}</td>
                    <td>{{date('m/d/Y H:i:s', strtotime($advertisement['updated_at']))}}</td>
                    <td><a href="{{url('advertisement/view')}}/{{$advertisement['id']}}"><i class="material-icons">edit</i></a><a href="{{url('advertisement/view_details')}}/{{$advertisement['id']}}"><i class="material-icons">visibility</i></a><a onclick="return confirm('Are you sure you want delete this?')" href="{{url('delete_advertisement')}}/{{$advertisement['id']}}"><i class="material-icons">delete</i></a></td></td>
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
<script src="{{asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/data-tables/js/dataTables.select.min.js')}}"></script>
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/data-tables.js')}}"></script>
<script type="text/javascript"></script>
@endsection