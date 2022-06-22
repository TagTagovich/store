$(document).ready(function() {
	
	$(".custom-label").each(function(index, element) {

		var elements = $(".custom-place-label");
		var varIndex = elements[index];
		$(element).html( varIndex );

	});
});
	//$(".add-entry").wrap( "<div class='btn-group'></div>" );
	//$(".remove-collection-entry").wrap( "<div class='btn-group'></div>" );


