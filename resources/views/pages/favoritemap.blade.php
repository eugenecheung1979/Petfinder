@extends('main')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-8">	
		<input hidden id="addressList" value="{{json_encode($addresslist)}}" />
		<input hidden id="favList" value="{{json_encode($favlist)}}" />
			<div class="card">
				<div class="card-body">
					<div id="map" style="width:100%;height:400px;">
					</div>
				</div>
			</div>	
			<hr>		
				<center><button type="button" class="btn btn-secondary btn-lg" id="backBtn">Back</button></center>
		</div>	

	</div>

</div>	
@stop

@section('extrascripts')	
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAj6ugUtdPocu-e2XS_susYzJacd2pC03M&callback=initMap"
    async defer></script>   
    <script type="text/javascript" src="/js/jquery.googlemap.js"></script> 
    <script src="/js/favoritemap.js"></script>    
@stop