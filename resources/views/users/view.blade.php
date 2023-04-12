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
          <h6 class="mb-2 mt-2"><i class="material-icons">error_outline</i> Personal Info</h6>
          <table class="striped">
            <tbody>
              <tr>
                <td>First Name:</td>
                <td>{{$user->first_name}}</td>
              </tr>
              <tr>
                <td>Last Name:</td>
                <td>{{$user->last_name}}</td>
              </tr>
              <tr>
                <td>Email:</td>
                <td>{{$user->email}}</td>
              </tr>
              <tr>
                <td>Gender:</td>
                <td>{{$user->gender}}</td>
              </tr>
              <tr>
                <td>Profile Image:</td>
                <td><img src="{{$user->profile_image}}" height="100px" width="100px"></td>
              </tr>
              <tr>
                <td>City:</td>
                <td>{{$user->city}}</td>
              </tr>
              <tr>
                <td></td>
                <td><a href="{{url('user')}}" type="button" class="btn btn-light">Back</a></td>
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