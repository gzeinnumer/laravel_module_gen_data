@include('layout.top')
<div class="container">
  <br>
  <div class="row">
    <div class="col">

    </div>
    <div class="col-auto">
      <a href="{{ route('modulegendata.updatetable') }}" type="submit" class="btn btn-primary btn-sm">Generate New Table</a>
    </div>
  </div>
  <div class="row">
    @if(session('sukses'))
    <div class="alert alert-success" role="alert">
      {{session('sukses')}}
    </div>
    @endif
  </div>
  <br>

  @include('modulegendata.index-tables')

</div>
@include('layout.bottom')