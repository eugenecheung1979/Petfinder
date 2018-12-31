/*
*
*/
$(function(){
	var locations = {};
	var addresslist = JSON.parse($("#addressList").val());
	var favlist = JSON.parse($("#favList").val());
	var geoCodeAddress = function(){
		var deferreds = [];
		$.each(addresslist, function(pid, add){
			var url = geocodeURL + "&address=" + add.replace(/\s+/g, '+');	
			
			var defer = $.getJSON(url, function(data){
					locations[pid] = [];
					locations[pid][0] = parseFloat(data.results[0].geometry.location.lat);
					locations[pid][1] = parseFloat(data.results[0].geometry.location.lng);

					//console.log(locations[pid][0] + "  " + locations[pid][1]);
				});	
			if(defer)
				deferreds.push(defer);
		});
		return deferreds;
	}

	$.when.apply($, geoCodeAddress())		
		  .then(function(){
			$("#map").googleMap({
     			 zoom: 3, // Initial zoom level (optional)
     			 type: "ROADMAP" // Map type (optional)
    		});

			console.log("locations is ready!");

    		$.each(locations, function(pid, loc){
    			console.log("prepare for making marker at " + loc[0] + ":" + loc[1]);
    			$("#map").addMarker({
      				coords: loc, // GPS coords
      				title: favlist[pid]['name'] + '(' +  favlist[pid]['animal'] + ')'
    			})
    		})
		});

	$("#backBtn").on('click', function(){
		window.history.back();
	});	  
})