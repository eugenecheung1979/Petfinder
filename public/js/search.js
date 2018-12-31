/*
*
*
*/

$(function(){
	var age = gender = size = state = breed = "";

	var ok = 0;

	var petData = {};

	var curPage = 1;
	
	var getConfigs = function(){
		return $.getJSON("/getConfig", function(data){
			configs = data;
		});
	}

	var getBreedlist = function(){
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


	var getSelectorsVal = function(){
		age = $("#ageSelector").val();
		size = $("#sizeSelector").val();
		gender = $("#genderSelector").val();
		state = $("#stateSelector").val();
		breed = $("#breedSelector").val();
	}


	var replacePageNav = function(currentPage, totalPages){
		var nav = "";

		if(currentPage > 1)
			nav += "  <li class=\"page-item\"><a class=\"page-link\" href=\"javascript:void(null);\" id=\"pagelinkPrev\" style=\"cursor: pointer;\">Previous</a></li>\n";
		for(var i = 1; i <= totalPages; i++){
			if(i == currentPage)
				nav += "     <li class=\"page-item active\"><a class=\"page-link\" href=\"javascript:void(null);\" id=\"pagelink-" + i + "\" style=\"cursor: pointer;\">" + i + "</a></li>\n";
			else
				nav += "     <li class=\"page-item\"><a class=\"page-link\" href=\"javascript:void(null);\" id=\"pagelink-" + i + "\" style=\"cursor: pointer;\">" + i + "</a></li>\n";
		}
		if(currentPage < totalPages)
			nav += "  <li class=\"page-item\"><a class=\"page-link\" href=\"javascript:void(null);\" id=\"pagelinkNext\" style=\"cursor: pointer;\">Next</a></li>\n";

		$("#pageNav").empty().append(nav);
	}

	var replacePetShow = function(pets){
		
		var showpet = "";
		$.each(pets, function(pid, pData){
			//console.log(pid + " is being processed!");
			showpet += "   <div class=\"col-3\">";

			showpet += "     <div class=\"thumbnail\" id=\"pet-" + pid + "\">\n";

			showpet += "         <a href=\"/details/" + pData['animal'] + "/" + pid + "\">\n";

			if(pData['smallpic'])
				showpet += "             <img id=\"petImg-" + pid + "\" src=\"" + pData['smallpic'] + "\" class=\"rounded float-left\" alt=\"Lights\" width=\"250\" height=\"300\">\n";
			else
				showpet += "             <img id=\"petImg-" + pid + "\" src=\"#\" class=\"rounded float-left\" alt=\"Lights\" width=\"250\" height=\"300\">\n";

			showpet += "         </a>\n";

			showpet += "         <div class=\"caption\">\n";

			showpet += "               <p><span id=\"petName-" + pid + "\">" + pData['name'] + "</span>, <span id=\"petSex-" + pid + "\">" + pData['sex'] + "</span>, <span id=\"petCity-" + pid + "\">" + pData['city'] + "</span></p>\n";

			showpet += "         </div>\n"

			showpet += "     </div>\n";

			showpet += "   </div>\n";
		});
		//console.log("new pet html: " + showpet);
		$("#showlist").empty().append(showpet);
	}


	/**************************************************/


	//retrieve config, breedlist, lastsearchresult from server before loading page
	$.when(getConfigs(), getBreedlist(), getLastSearch())
	.then(function(){			
			if(configs){
				$("#ageSelector").prop('disabled', false);
				$("#sizeSelector").prop('disabled', false);
				$("#genderSelector").prop('disabled', false);
				$("#stateSelector").prop('disabled', false);				

				

				//console.log(searchURL);
				ok++;
			}
			if(breedlist){
				$("#breedSelector").prop('disabled', false);	
				ok++;			
			}
			if(ok == 2){
				getSelectorsVal();
				curPage = parseInt($("#currentPage").val());
				console.log("current animal: " + animal);
				searchURL += configs['key'] + "&count=" + configs['defaultCount'] + "&animal=" + animal.toLowerCase() + "&format=json";
			}

	})
	.fail(function(xhr, textStatus, errorThrown){
			console.log("something wrong: ")
			console.log(errorThrown);
	});


	//search criteria
	$("select").on("change", function(){
		if(ok < 2){
			alert("Failed to retrieve data, contact to admin");
			return;
		}
		var id = $(this).attr('id');
		getSelectorsVal();

		var url = searchURL + "&location=" + state;

		if(breed != 'Any'){
			url += "&breed=" + breed.replace(/\s+/g, '+');
		}

		if(size != 'Any'){
			url += "&size=" + size;
		}

		if(gender != 'Any'){
			url += "&gender=" + gender;
		}

		if(age != 'Any'){
			url += "&age=" + age;
		}

		console.log(url);
		var dataSent = {};
		dataSent['url'] = url;

		$.ajaxSetup({
   			 headers: {
        		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   			 }
		});

		$.ajax({
			type: 'POST',
	        url: '/searchPet',
	        data: dataSent,
	        cache: false,
	        dataType: 'json'				
		}).done(function(data){
			petData = data;
			var sizeOfPetData = Object.size(petData);
			//showObj(petData);
			//console.log("size of petData: " + Object.size(petData));
			if(sizeOfPetData > 0){

				curPage = 1;
				$("#currentPage").val(curPage);
				

				//generate a page data and replace old pet result with it
				var pageData = generatePageData(curPage, configs['numOfPetsPerPage'], petData);

				//console.log("pageData size: " + Object.size(pageData));				
				replacePetShow(pageData);

				//replace pagination nav
				var totalpage = Math.ceil(sizeOfPetData / parseInt(configs['numOfPetsPerPage']));
				//console.log("size of petData: " + sizeOfPetData + "    totalpage: " + totalpage);
				replacePageNav(curPage, totalpage);
				

			}
			else{
				alert("No record found!");
			}

		}).fail(function(xhr, textStatus, errorThrown){
			console.log("something wrong: ")
			console.log(errorThrown);			
		});


	});


	//pagination
	$("#pageNav").on('click', 'a', function(){
		var id = $(this).attr("id");
		//console.log("link " + id + " is clicking");
		if(id == 'pagelinkPrev'){
			curPage--;
			$("#currentPage").val(curPage);
		}
		else if (id == 'pagelinkNext'){
			curPage++;
			$("#currentPage").val(curPage);
		}
		else{
			var tmp = id.split('-');
			var pageNum = tmp[1];
			curPage = parseInt(pageNum);
			$("#currentPage").val(curPage);
		}
		//console.log("current page: " + curPage);

		var sizeOfPetData = Object.size(petData);
		//showObj(petData);
		//console.log("size of petData: " + Object.size(petData));
		if(sizeOfPetData > 0){		
			//generate a page data and replace old pet result with it
			var pageData = generatePageData(curPage, configs['numOfPetsPerPage'], petData);

			//console.log("pageData size: " + Object.size(pageData));				
			replacePetShow(pageData);

			//replace pagination nav
			var totalpage = Math.ceil(sizeOfPetData / parseInt(configs['numOfPetsPerPage']));
			//console.log("size of petData: " + sizeOfPetData + "    totalpage: " + totalpage);
			replacePageNav(curPage, totalpage);			
		}
		else{
			alert("No record found!");
		}
	});
});
