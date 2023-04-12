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
          <h4 class="card-title">Edit Question</h4>
          <form id="accountForm" method="post" action="{{ route('question.update',$question->id)}}}">
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
            <div class="answer_div">
              @if($question->questionAnswer)
                 <?php $i = 0;?>
                 @foreach($question->questionAnswer as $key=>$answer)
                    <div class="row answers">
                      <div class="input-field col s12 display-flex">
                        <textarea id="message5" name="old_answer[{{$answer->id}}]" class="materialize-textarea" required="required">{{$answer->answer}}</textarea>
                         @if($i != 0)
                         <i style="cursor:pointer" class="material-icons right remove_answer_text close-btn">close</i>
                         @endif
                        <label for="message">Answer</label>
                        @if ($errors->has('answer'))
                          <small class="errorTxt1" style="color: red;">{{ $errors->first('answer') }}</small>
                        @endif
                      </div>
                    </div>
                  <?php $i++; ?>
                  @endforeach
              @endif
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