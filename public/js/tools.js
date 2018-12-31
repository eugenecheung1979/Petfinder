/*
Tools
*/

function show(obj, stack) {
    for (var property in obj) {
        if (obj.hasOwnProperty(property)) {
            if (typeof obj[property] == "object") {
                show(obj[property], stack + '.' + property);
            } else {
                console.log(property + "   " + obj[property]);
                //$('#output').append($("<div/>").text(stack + '.' + property))
            }
        }
    }
}

function showObj(obj){
	show(obj, '');
}

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
}


function generatePageData(currentPage, numOfElementsPerPage, data){
        var start = numOfElementsPerPage * ( currentPage - 1 ) + 1;
        var end = start + numOfElementsPerPage - 1;

        var retlist = {};

        //console.log("currentPage: " + currentPage + "     numOfElementsPerPage: " + numOfElementsPerPage);
        //console.log("start: " + start + "      end: " + end);

        if(data){
            var count = 1;            
            $.each(data, function(id, rec){
                //console.log("count is: " + count);
                if(count >= start && count <= end){
                    //showObj(rec);
                    //console.log("copying rec " + id);
                    retlist[id] = rec;
                }
                if(count > end)
                    return;

                count++;
            });
        }

        //showObj(retlist);
        return retlist;
}