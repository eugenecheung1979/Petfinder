 @extends('main')
 @section('content')
<div class="container">
	<div class="row">
		<div class="col-6">
			<input hidden id="addressInput" value="{{$addressInput}}" />
			<input hidden id="googleKey" value="{{$googleKey}}" />
			<input hidden id="petDetail" value="{{json_encode($petDetail)}}" />
			<div class="card">
				<img class="card-img-top" src="{{$petDetail['largepic']}}" class="rounded float-left" alt="{{$petDetail['name']}}" width="350" height="400">
				<div class="card-body">
					<h5 class="card-title">{{$petDetail['name']}}</h5>
					<h6 class="card-subtitle mb-2 text-muted">{{$subtitle}}</h6>
					<ul class="list-group list-group-flush">
						<li class="list-group-item">{{$petDetail['age']}}</li>
    					<li class="list-group-item">{{$petDetail['sex']}}</li>
    					<li class="list-group-item">{{$petDetail['size']}}</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="col-6">
			<div class="card">
				<div class="card-body">
					<div id="map" style="width:100%;height:400px;">
					</div>
					<ul class="list-group list-group-flush">
						<li class="list-group-item">{{$petDetail['street']}}</li>
						<li class="list-group-item">{{$petDetail['city']}}</li>
						<li class="list-group-item">{{$petDetail['zip']}}, {{$petDetail['state']}}</li>
						@if(isset($petDetail['phone']))
							<li class="list-group-item">{{$petDetail['phone']}}</li>
						@endif
						@if(isset($petDetail['email']))
							<li class="list-group-item">{{$petDetail['email']}}</li>
						@endif						
					</ul>
				</div>
			</div>
		</div>
		<br>
		@if(!$isFavorite)
			<center>
				<button type="button" class="btn btn-success btn-lg" id="doBtn">Interested</button>
				<button type="button" class="btn btn-secondary btn-lg" id="cancelBtn">Not Interested</button>
			</center>
		@else		
			<center><button type="button" class="btn btn-secondary btn-lg" id="cancelBtn">Back</button></center>
		@endif
	</div>
</div>
 @stop

@section('extrascripts')	
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAj6ugUtdPocu-e2XS_susYzJacd2pC03M&callback=initMap"
    async defer></script>   
    <script type="text/javascript" src="/js/jquery.googlemap.js"></script> 
    <script src="/js/mymap.js"></script>
    <script src="/js/favorite.js"></script>
@stop