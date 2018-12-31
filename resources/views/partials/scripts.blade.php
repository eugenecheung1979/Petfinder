<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
<script src="/js/tools.js"></script>
<script>
	//global variable
	var configs = {};
	var breedlist = {};
	var lastsearch = {};
	var animal = 'Dog';

	var searchURL = "http://api.petfinder.com/pet.find?key=";

	var rootURL = "http://petfinder.yucheung.com";
	
	var geocodeURL = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyAj6ugUtdPocu-e2XS_susYzJacd2pC03M";

	var getConfigs = function(){
		return $.getJSON("/getConfig", function(data){			
				configs = data;
		});
	}

	var getBreedlist = function(){
		if($("#currentAnimal").length)
			animal = $("#currentAnimal").val();
		
		//console.log('currentAnimal: ' + animal);
		return $.getJSON("/getBreedlist/" + animal, function(data){			
				breedlist = data;
		});
	}

	var getLastSearch = function(){
		return $.getJSON("/getLastSearch", function(data){
			lastsearch = data;
		});
	}

</script>