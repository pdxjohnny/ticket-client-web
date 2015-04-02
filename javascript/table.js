$(document).ready( function () {
	console.log("loaded")
	$("#clear")[0].onclick = clear;
});

function download( filename, text ) {
	var link = document.createElement('a');
	link.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
	link.setAttribute('download', filename);
	link.click();
}

function clear() {
	$.get( "/clear.php", function( data ) {
	 jQuery.mobile.changePage(window.location.href, {
        	allowSamePageTransition: true,
        	transition: 'none',
        	reloadPage: true
	});
	});
}

function make_table ( table )
{
	var html = '';
	var headers = false;
	for ( var row in table )
	{
		if ( !headers )
		{
			html += "<thead><tr>";
			for ( var column in table[row] )
			{
				html += "<th>" + column + "</th>";
			}
			html += "</tr></thead><tbody>";
			headers = true;
		}
		html += "<tr>";
		for ( var column in table[row] )
		{
			html += "<td>" + table[row][column] + "</td>";
		}
		html += "</tr>";
	}
	html += "</tbody>";
	return html;
}
