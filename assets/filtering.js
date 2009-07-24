/*-----------------------------------------------------------------------------
	Text Box Interface
-----------------------------------------------------------------------------*/
	
	jQuery(document).ready(function() {
		jQuery('.field-textbox').filterTextBox();
		
		jQuery('.subsection .actions select + a').click(function() {
			jQuery('.field-textbox:not(.initialised)').filterTextBox();
		});
	});
	
/*---------------------------------------------------------------------------*/