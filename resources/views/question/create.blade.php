<style>
.close-btn{
	margin:15px 0px 15px 
}
</style>

{{-- extend layout --}}
@extends('layouts.contentLayoutMaster')

{{-- Page title --}}
@section('title','Form Layouts')

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
          <h4 class="card-title">Add Question</h4>
          <form id="accountForm" method="post" action="{{ route('question.store') }}">
            @csrf
            <div class="row">
              <div class="input-field col m6 s12">
                <select name="cat_id">
                  @foreach($categories as $category)
                    <option value="{{$category->id}}">{{$category->category}}</option>
                  @endforeach
                </select>
                <label>Categories</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <textarea id="message5" name="question" class="materialize-textarea"></textarea>
                <label for="message">Question</label>
                @if ($errors->has('question'))
                    <small class="errorTxt1" style="color: red;">{{ $errors->first('question') }}</small>
                @endif
              </div>
            </div>
            <div class="answer_div">
              <div class="row">
                <div class="input-field col s12">
                  <textarea id="message5" name="answer[]" class="materialize-textarea" required="required"></textarea>
                  <label for="message">Answer</label>
                  @if ($errors->has('answer'))
                    <small class="errorTxt1" style="color: red;">{{ $errors->first('answer') }}</small>
                  @endif
                </div>
              </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                  <button class="btn indigo waves-effect waves-light left add_answer_text" type="button" name="action">Add Answer
                  </button>
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