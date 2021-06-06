function htmlDecode(value){
    return $("<textarea/>").html(value).text();
}