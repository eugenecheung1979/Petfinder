/*
*
*/
$(function(){
	var delPool = [];

	$("input[type='checkbox']").on('change', function(){
		var tmp = $(this).attr('id').split("-");
		var pid = tmp[1];
		console.log("pid: " + pid);
		if($(this).is(':checked')){
			//add pid into delPool
			delPool.push(pid);
		}
		else{
			//remove pid from delPool
			delPool = delPool.filter(function(el){
				return el != pid;
			});
		}

		//for delpool
		var str = "";
		for(var i = 0; i < delPool.length; i++)
			str += " " + delPool[i];
		console.log("delPool: " + str);

		if(delPool.length > 0)
			$("#delBtn").attr("disabled", false);
		else
			$("#delBtn").attr("disabled", true);
	});

	$("#delBtn").on('click', function(){
		var data = {};
		data.pids = delPool;

		$.ajaxSetup({
   			 headers: {
        		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   			 }
		});

		$.ajax({
			type: 'POST',
	        url: '/delFromFavoriteList',
	        data: data,
	        cache: false,
	        dataType: 'json'			

		}).done(function(data){
			if (typeof data['msg'] != undefined && data['msg'] ){
				var m = data['msg'];
				if(m == 'success'){
					alert('Completed successfully!');
					window.location.href = rootURL + "/showMyFavorites";
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


})