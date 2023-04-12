{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Users View')

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
@endsection

{{-- page content  --}}
@section('content')
<!-- users view start -->
<div class="section users-view">
  <!-- users view media object start -->
  <!-- users view media object ends -->
  <!-- users view card data start -->
  <!-- users view card data ends -->

  <!-- users view card details start -->
  <div class="card">
    <div class="card-content">
      <div class="row">
        <div class="col s12">
          <h6 class="mb-2 mt-2"><i class="material-icons">error_outline</i> Advertisement Details</h6>
          <table class="striped">
            <tbody>
              <tr>
                <td>Name:</td>
                <td>{{$advertisement->name}}</td>
              </tr>
              <tr>
                <td>Description:</td>
                <td>{{$advertisement->description}}</td>
              </tr>
              <tr>
                <td>Action Name:</td>
                <td>{{$advertisement->action_name}}</td>
              </tr>
              <tr>
                <td>Action Value:</td>
                <td>{{$advertisement->action_value}}</td>
              </tr>
              <tr>
                <td>Start Date:</td>
                <td>{{$advertisement->start_date}}</td>
              </tr>
              <tr>
                <td>End Date:</td>
                <td>{{$advertisement->end_date}}</td>
              </tr>
              <tr>
                <td>Images:</td>
                <td>@if(isset($advertisement->advertisementImages))
                           @foreach($advertisement->advertisementImages as $image)
                              <img src="{{$image->image}}" width="100px" height="100px">
                           @endforeach
                        @endif
                    </td>
              </tr>
              <tr>
                <td></td>
                <td><a href="{{url('advertisement')}}" type="button" class="btn btn-light">Back</a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- </div> -->
    </div>
  </div>
  <!-- users view card details ends -->

</div>
<!-- users view ends -->
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/page-users.js')}}"></script>
@endsection