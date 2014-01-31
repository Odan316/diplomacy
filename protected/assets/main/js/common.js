/**
 * User: Sergey
 * Date: 10.10.13
 */

window.url_root = "/diplomacy/";

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};