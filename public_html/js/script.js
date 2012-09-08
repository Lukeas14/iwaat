var redactor_config = {
	buttons: [
		'formatting', '|', 'bold', 'italic', 'deleted', '|',
		'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
		'image', 'video', 'file', 'table', 'link', '|',
		'fontcolor', 'backcolor', '|', 
		'alignleft', 'aligncenter', 'alignright', 'justify', '|',
		'horizontalrule'
	]
};

function external_link(url){
	var window_open = window.open(url);
	return false;
}

function character_count($input_elem, $count_elem, max_characters){
	var char_remaining = max_characters - $input_elem.val().length;
	$count_elem.text(char_remaining);

	$input_elem.keyup(function(){
		char_remaining = max_characters - $input_elem.val().length;
		$count_elem.text(char_remaining);
	});

	$input_elem.change(function(){
		char_remaining = max_characters - $input_elem.val().length;
		$count_elem.text(char_remaining);
	});
}

function strip_tags(html){
   var tmp = document.createElement("DIV");
   tmp.innerHTML = html.replace(/(<\/?[^>]+>)/gi, ' ').replace(/\s{2,}/g,' ');
   return tmp.textContent||tmp.innerText;
}