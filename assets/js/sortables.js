// Jquery function for order fields
// When the page is loaded define the current order and items to reorder
$(document).ready( function(){
	/* Call the container items to reorder questions */
	$("#questions-list").sortable({ 
			opacity: 0.6, 
			cursor: "move",
			connectWith: "#questions-list",
			update: function(event, ui) {
				var list = $(this).sortable("serialize");
				$.post("questions.php?op=order", list );
			},
			receive: function(event, ui) {
				var list = $(this).sortable("serialize");                    
				$.post("questions.php?op=order", list );                      
			}
		}
	).disableSelection();
	/* Call the container items to reorder categories */
	$("#categories-list").sortable({
			opacity: 0.6,
			cursor: "move",
			connectWith: "#categories-list",
			update: function(event, ui) {
				var list = $(this).sortable("serialize");
				$.post("categories.php?op=order", list );
			},
			receive: function(event, ui) {
				var list = $(this).sortable("serialize");
				$.post("categories.php?op=order", list );
			}
		}
	).disableSelection();
});