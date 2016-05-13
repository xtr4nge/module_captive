function setCheckbox(item, param) {
    if (document.getElementById(item.id).checked) {
        value = "1";
    } else {
        value = "0";
    }
    $.getJSON('../api/includes/ws_action.php?api=/config/module/captive/'+param+'/'+value, function(data) {});
}

function setOption(item, param) {
	value = $("#"+item).val();
    $.getJSON('../api/includes/ws_action.php?api=/config/module/captive/'+param+'/'+value, function(data) {});
}

function setRadio(item, param) {
    value = document.getElementById(item.id).value
	//console.log(document.getElementById(item.id).checked);
	//console.log(document.getElementById(item.id).value);
    $.getJSON('../api/includes/ws_action.php?api=/config/module/captive/'+param+'/'+value, function(data) {});
}