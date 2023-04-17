{{-- extend layout --}}
@extends('layouts.contentLayoutMaster')

{{-- Page title --}}
@section('title','View Question')

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
          <h4 class="card-title">View Question</h4>
          <form id="accountForm" method="post" action="{{url('/')}}/update_advertisement/{{$advertisement->id}}">
            @csrf
            <div class="row">
              <div class="input-field col m6 s12">
                <select name="status" class="status">
                   <option @if($advertisement->status == 'Pending') selected @endif value="Pending">Pending</option>
                   <option @if($advertisement->status == 'Approved') selected @endif value="Approved">Approved</option>
                   <option @if($advertisement->status == 'Rejected') selected @endif value="Rejected">Rejected</option>
                </select>
                <label>Status</label>
              </div>
              
              <div @if($advertisement->status != 'Approved') style="display:none" @endif class="input-field col m6 s12 hide_date_text">
                <input type="text" value="{{ date('M d, Y', strtotime(str_replace('/', '-', $advertisement->start_date))) }}" name="start_date" class="datepicker start_date" id="start_date" data-error=".errorTxt1">
                <label for="dob" class="active">Start Date</label>
                @if ($errors->has('start_date'))
                  <small class="errorTxt1" style="color: red;">{{ $errors->first('start_date') }}</small>
                @endif
              </div>
            </div>
            <div @if($advertisement->status != 'Approved') style="display:none" @endif class="row hide_date_text">
              <div class="input-field col m6 s12">
                <input type="text" value="{{ date('M d, Y', strtotime(str_replace('/', '-', $advertisement->end_date))) }}" name="end_date" class="datepicker end_date" id="dob" data-error=".errorTxt1">
                <label for="dob" class="active">End Date</label>
                @if ($errors->has('end_date'))
                  <small class="errorTxt1" style="color: red;">{{ $errors->first('end_date') }}</small>
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
{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
@endsection

{{-- page scripts --}}
@section('page-script')
<script src="{{asset('js/scripts/page-ad.js')}}"></script>
@endsection
