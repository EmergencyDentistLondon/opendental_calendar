function confirmAction(msg, url){
	var answer = confirm(msg);
	if(answer){
		location.replace(url);
	}
}
