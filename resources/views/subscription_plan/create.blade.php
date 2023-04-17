{{-- extend layout --}}
@extends('layouts.contentLayoutMaster')

{{-- Page title --}}
@section('title','Add Subscription Plan')

{{-- vendor style --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/flag-icon/css/flag-icon.min.css')}}">
@endsection

{{-- page content --}}
@section('content')
<div class="seaction">
    <!-- Form Advance -->
    <div class="col s12 m12 l12">
      <div id="Form-advance" class="card card card-default scrollspy">
        <div class="card-content">
          <h4 class="card-title">Add Subscription Plan</h4>
          <form id="accountForm" method="post" enctype="multipart/form-data" action="{{url('')}}/subscription_plan/store">
            @csrf
            <div class="row">
              <div class="input-field col m6 s12">
                <input id="last_name" name="title" type="text">
                <label for="last_name">Title</label>
                @if ($errors->has('title'))
                    <small class="errorTxt1" style="color: red;">{{ $errors->first('title') }}</small>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <textarea id="message5" name="description" class="materialize-textarea"></textarea>
                <label for="message">Description</label>
                @if ($errors->has('description'))
                    <small class="errorTxt1" style="color: red;">{{ $errors->first('description') }}</small>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="input-field col m6 s12">
                <input type="text" name="price" id="dob">
                <label for="dob">Price</label>
                @if ($errors->has('price'))
                    <small class="errorTxt1" style="color: red;">{{ $errors->first('price') }}</small>
                @endif
              </div>
              <div class="input-field col m6 s12">
                <input type="number" name="ads" id="dob">
                <label for="dob">Number of Ads</label>
                @if ($errors->has('ads'))
                    <small class="errorTxt1" style="color: red;">{{ $errors->first('ads') }}</small>
                @endif
              </div>
            </div>
            <div class="row">
                <div class="col s12 input-field">
                  <select name="status">
                    <option value="Active">Active</option>
                    <option value="Deactivate">Deactivate</option>
                  </select>
                  <label>Status</label>
                  @if ($errors->has('status'))
                      <small class="errorTxt1" style="color: red;">{{ $errors->first('status') }}</small>
                   @endif
                </div>
            </div>
            <div class="row">
              <div class="row">
                <div class="input-field col s12">
                  <button class="btn indigo waves-effect waves-light right" type="submit" name="action">Submit
                    <i class="material-icons right">send</i>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection