{{-- extend layout --}}
@extends('layouts.contentLayoutMaster')

{{-- Page title --}}
@section('title','Edit General Question')

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
          <h4 class="card-title">Edit General Question</h4>
          <form id="accountForm" method="post" action="{{ route('general_question.update',$question->id)}}}">
            <input type="hidden" name="_method" value="PATCH">
            @csrf
            <div class="row">
              <div class="input-field col m6 s12">
                <select name="cat_id">
                  @foreach($categories as $category)
                    <option @if($question->cat_id == $category->id) selected @endif value="{{$category->id}}">{{$category->category}}</option>
                  @endforeach
                </select>
                <label>Categories</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <textarea id="message5" name="question" class="materialize-textarea">{{$question->question}}</textarea>
                <label for="message">Question</label>
                @if ($errors->has('question'))
                    <small class="errorTxt1" style="color: red;">{{ $errors->first('question') }}</small>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="row">
                <div class="input-field col s11">
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