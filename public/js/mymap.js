/*
*
*/

$(function(){	

	var location = [];

	var address = $("#addressInput").val().replace(/\s+/g, '+');
	//console.log("adressinput: " + address);
	geocodeURL += "&address=" + address;
	//console.log("geocodeURL: " + geocodeURL);
	$.when(
		$.getJSON(geocodeURL, function(data){
			//showObj(data);
			//console.log(data);
			location[0] = parseFloat(data.results[0].geometry.location.lat);
			location[1] = parseFloat(data.results[0].geometry.location.lng);
			//location = new google.maps.LatLng(parseFloat(data.results[0].geometry.location.lat), parseFloat(data.results[0].geometry.location.lng));
			
		  })
		).then(function(){
			/*
			$("#map").googleMap({
				zoom: 5,
				coords: location,
			});
			*/
			$("#map").googleMap({
     			 zoom: 3, // Initial zoom level (optional)
     			 type: "ROADMAP" // Map type (optional)
    		});
			console.log('location is ready!');
			$("#map").addMarker({
      			coords: location, // GPS coords
      			id: 'marker1' // Unique ID for your marker
    		})
		});
});
