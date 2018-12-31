@if(Session::has('success'))
<div class="alert alert-success">
  <strong>Success!</strong> {{Session::get('success')}}
</div>

@elseif(Session::has('failure'))
<div class="alert alert-danger">
  <strong>Error!</strong> {{Session::get('failure')}}
</div>
@endif