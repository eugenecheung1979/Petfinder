/*
*
*/
$(function(){

	var petDetail = JSON.parse($("#petDetail").val());	
	//showObj(petDetail);

	if(!petDetail)
		$("#doBtn").attr('diabled', "diabled");

	$("#doBtn").on('click', function(){
		if(!petDetail.name){
			alert('name is required');
			return;
		}
		if(!petDetail.animal){
			alert('animal property is required');
			return
		}
		if(!petDetail.size){
			alert('size is required');
			return
		}
		if(!petDetail.breeds){
			alert('breeds property is required');
			return
		}
		if(!petDetail.sex){
			alert('gender is required');
			return
		}
		if(!petDetail.street){
			//alert('street property is required');
			petDetail.street = "Unkown";			
		}
		if(!petDetail.city){
			//alert('city property is required');
			//return
			petDetail.city = "Unkown";
		}	
		if(!petDetail.state){
			//alert('state property is required');
			//return
			petDetail.state = "Unknown";
		}
		if(!petDetail.zip){
			//alert('zip property is required');
			//return
			petDetail.state = "Unkown";
		}		
		$.ajaxSetup({
   			 headers: {
        		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   			 }
		});

		$.ajax({
			type: 'POST',
	        url: '/addToFavoriteList',
	        data: petDetail,
	        cache: false,
	        dataType: 'json'			

		}).done(function(data){
			if (typeof data['msg'] != undefined && data['msg'] ){
				var m = data['msg'];
				if(m == 'success'){
					alert('Completed successfully!');
					window.location.href = rootURL;
				}
				else if (m == 'emptyinput'){
					alert('Data sent is empty!');
				}
				else if (m == 'databasebad'){
					alert('Encount an database issue ');
				}
				else{
					alert("Debug: \n" + m);
				}
			}
		}).fail(function(xhr, textStatus, errorThrown){
			console.log("something wrong: ")
			console.log(errorThrown);			
		});
	});

	$("#cancelBtn").on('click', function(){
		window.history.back();
	});
});