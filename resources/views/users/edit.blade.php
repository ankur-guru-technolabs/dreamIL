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
            <i class="material-icons mr-1">person_outline</i><span>Account</span>
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
          <!-- users edit media object start -->
<!--           <div class="media display-flex align-items-center mb-2">
            <a class="mr-2" href="#">
              <img src="{{asset('images/avatar/avatar-11.png')}}" alt="users avatar" class="z-depth-4 circle"
                height="64" width="64">
            </a>
            <div class="media-body">
              <h5 class="media-heading mt-0">Avatar</h5>
              <div class="user-edit-btns display-flex">
                <a href="#" class="btn-small indigo">Change</a>
                <a href="#" class="btn-small btn-light-pink">Reset</a>
              </div>
            </div>
          </div> -->
          <!-- users edit media object ends -->
          <!-- users edit account form start -->
          <form id="accountForm" method="post" action="{{ route('user.update',$user->id)}}">
            <input type="hidden" name="_method" value="PATCH">
            @csrf
            <div class="row">
              <div class="col s12 m6">
                <div class="row">
                  <div class="col s12 input-field">
                    <input id="name" name="first_name" type="text" class="validate" value="{{$user->first_name}}"
                      data-error=".errorTxt1">
                    <label for="username">First Name</label>
                     @if ($errors->has('first_name'))
                        <small class="errorTxt1" style="color: red;">{{ $errors->first('first_name') }}</small>
                     @endif
                  </div>
                  <div class="col s12 input-field">
                    <input id="name" name="last_name" type="text" class="validate" value="{{$user->last_name}}"
                      data-error=".errorTxt1">
                    <label for="username">Last Name</label>
                     @if ($errors->has('last_name'))
                        <small class="errorTxt1" style="color: red;">{{ $errors->first('last_name') }}</small>
                     @endif
                  </div>
                  <div class="col s12 input-field">
                    <input id="email" name="email" type="email" class="validate" value="{{$user->email}}">
                    <label for="email">E-mail</label>
                    @if ($errors->has('email'))
                        <small class="errorTxt1" style="color: red;">{{ $errors->first('email') }}</small>
                     @endif
                  </div>
                </div>
              </div>
              <div class="col s12 m6">
                <div class="row">
                  <div class="col s12 input-field">
                    <select name="status">
                      <option @if($user->status == 'active') selected @endif value="active">Active</option>
                      <option  @if($user->status == 'deactivate') selected @endif value="deactivate">Deactivate</option>
                    </select>
                    <label>Status</label>
                    @if ($errors->has('status'))
                        <small class="errorTxt1" style="color: red;">{{ $errors->first('status') }}</small>
                     @endif
                  </div>
                  <div class="col s12 input-field">
                    <input id="phone" name="phone" type="text" class="validate" value="{{$user->phone}}">
                    <label for="phone">Phone</label>
                    @if ($errors->has('phone'))
                        <small class="errorTxt1" style="color: red;">{{ $errors->first('phone') }}</small>
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