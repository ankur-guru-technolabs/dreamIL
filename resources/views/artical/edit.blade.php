{{-- extend layout --}}
@extends('layouts.contentLayoutMaster')

{{-- Page title --}}
@section('title','Edit Artical')

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
          <h4 class="card-title">Edit Artical</h4>
          <form id="accountForm" method="post" enctype="multipart/form-data" action="{{ route('artical.update',$artical->id)}}">
             <input type="hidden" name="_method" value="PATCH">
            @csrf
            <div class="row">
              <div class="input-field col m6 s12">
                <select name="category_id">
                  @foreach($articalCategory  as $catgory)
                  <option @if($artical->category_id == $catgory->id) selected @endif value="{{$catgory->id}}">{{$catgory->category}}</option>
                  @endforeach
                </select>
                <label>Category</label>
              </div>
              <div class="input-field col m6 s12">
                <input id="last_name" value="{{$artical->name}}" name="name" type="text">
                <label for="last_name">Name</label>
                @if ($errors->has('name'))
                    <small class="errorTxt1" style="color: red;">{{ $errors->first('name') }}</small>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <textarea id="message5" name="description" class="materialize-textarea">{{$artical->description}}</textarea>
                <label for="message">Description</label>
                @if ($errors->has('description'))
                    <small class="errorTxt1" style="color: red;">{{ $errors->first('description') }}</small>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col m6 s12 file-field input-field">
                <div class="btn float-right">
                  <span>Image</span>
                  <input type="file"  name="image">
                </div>
                <div class="file-path-wrapper">
                  <input class="file-path validate"  type="text">
                </div>
                @if ($errors->has('image'))
                    <small class="errorTxt1" style="color: red;">{{ $errors->first('image') }}</small>
                @endif
              </div>
              <div class="input-field col m6 s12">
                <input type="text" name="author_name" value="{{$artical->author_name}}" id="dob">
                <label for="dob">Author Name</label>
                @if ($errors->has('author_name'))
                    <small class="errorTxt1" style="color: red;">{{ $errors->first('author_name') }}</small>
                @endif
              </div>
            </div>
            <div class="row">
                <div class="col s12 input-field">
                  <select name="status">
                    <option @if($artical->status == 'Active') selected @endif value="Active">Active</option>
                    <option  @if($artical->status == 'Deactivate') selected @endif value="Deactivate">Deactivate</option>
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