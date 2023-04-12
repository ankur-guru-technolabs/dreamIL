{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Users edit')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
@endsection

{{-- page content --}}
@section('content')
<!-- users edit start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
      <!-- <div class="card-body"> -->
      <ul class="tabs mb-2 row">
        <li class="tab">
          <a class="display-flex align-items-center active" id="account-tab" href="#account">
            <i class="material-icons mr-1">person_outline</i><span>Admin</span>
          </a>
        </li>
<!--         <li class="tab">
          <a class="display-flex align-items-center" id="information-tab" href="#information">
            <i class="material-icons mr-2">error_outline</i><span>Information</span>
          </a>
        </li> -->
      </ul>
      <div class="divider mb-3"></div>
      <div class="row">
        <div class="col s12" id="account">
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
          <form id="accountForm" method="post" action="{{url('/')}}/update_profile">
            @csrf
            <div class="row">
              <div class="col s12 m6">
                <div class="row">
                  <input type="hidden" name="user_id" value="{{$user->id}}">
                  <div class="col s12 input-field">
                    <input id="first_name" name="first_name" type="text" class="validate" value="{{$user->first_name}}"
                      data-error=".errorTxt1">
                    <label for="username">First Name</label>
                     @if ($errors->has('first_name'))
                        <small class="errorTxt1" style="color: red;">{{ $errors->first('first_name') }}</small>
                     @endif
                  </div>
                  <div class="col s12 input-field">
                    <input id="email" name="email" type="email" class="validate" value="{{$user->email}}">
                    <label for="email">Email</label>
                    @if ($errors->has('email'))
                        <small class="errorTxt1" style="color: red;">{{ $errors->first('email') }}</small>
                     @endif
                  </div>
                </div>
              </div>
              <div class="col s12 m6">
                <div class="row">
                  <div class="col s12 input-field">
                    <input id="name" name="last_name" type="text" class="validate" value="{{$user->last_name}}"
                      data-error=".errorTxt2">
                    <label for="name">Last Name</label>
                    @if ($errors->has('last_name'))
                        <small class="errorTxt1" style="color: red;">{{ $errors->first('last_name') }}</small>
                     @endif
                  </div>
                  <div class="col s12 input-field">
                    <input id="email" name="password" type="password" class="validate" value="">
                    <label for="email">Password</label>
                    @if ($errors->has('password'))
                        <small class="errorTxt1" style="color: red;">{{ $errors->first('password') }}</small>
                     @endif
                  </div>
                </div>
              </div>
              <div class="col s12 display-flex justify-content-end mt-3">
                <button type="submit" class="btn indigo">
                  Save changes</button>
                <button type="button" class="btn btn-light">Cancel</button>
              </div>
            </div>
          </form>
          <!-- users edit account form ends -->
        </div>
      </div>
      <!-- </div> -->
    </div>
  </div>
</div>
<!-- users edit ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
@endsection

{{-- page scripts --}}
@section('page-script')
<script src="{{asset('js/scripts/page-users.js')}}"></script>
@endsection