{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Artical List')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/flag-icon/css/flag-icon.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/jquery.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css"
  href="{{asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/select.dataTables.min.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/data-tables.css')}}">
@endsection

{{-- page content --}}
@section('content')
<div class="section section-data-tables">
  <div class="row">
    <div class="col s12">
      <div class="card">
        <div class="card-content">
          <h4 class="card-title">Artical List</h4>
          <div class="row">
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
            <div class="col s12">
              <table id="page-length-option" class="display">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Categorie</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Author Name</th>
                    <th>Status</th>
                    <th>Updated At</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($articals as $artical)
                  <tr>
                    <td>{{$artical['id']}}</td>
                    <td>@if(isset($artical['article_category']['category'])) {{$artical['article_category']['category']}} @endif</td>
                    <td>{{$artical['name']}}</td>
                    <td><p>{{$artical['description']}}</p></td>
                    <td><img height="50px" src="{{$artical['image']}}"></td>
                    <td>{{$artical['author_name']}}</td>
                    <td>{{$artical['status']}}</td>
                    <td>{{date('m-d-Y H:i:s', strtotime($artical['updated_at']))}}</td>
                    <td style="text-align:center;"><a href="{{ route('artical.edit',$artical['id'])}}"><i class="material-icons">edit</i></a><a onclick="return confirm('Are you sure you want delete this?')" href="{{url('delete_artical/delete')}}/{{$artical['id']}}"><i class="material-icons">delete</i></a></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/data-tables/js/dataTables.select.min.js')}}"></script>
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/data-tables.js')}}"></script>
@endsection