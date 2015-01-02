/**
 * User: Sergey
 * Date: 10.10.13
 */

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

function createList(selectId, selected, data){
    var select = $("#"+selectId);
    select.html("");
    for(var key in data){
        if(!data.hasOwnProperty(key)) continue;
        var option = $("<option>")
            .attr({
                'name': data[key],
                'value': key
            })
            .text(data[key]);
        if(selected == key) option.attr('selected', 'selected');
        select.append(option);

    }
}