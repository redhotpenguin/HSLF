jQuery(document).ready(function($) {

jQuery.fn.add_preview = function() {
	var self;
	if(this.is('input') || this.is('textarea')) {           
		this.keyup( function (e){   
			self = jQuery(this);
			id = self.attr('id');
			target = $('#'+id+'_preview');
			target.html( self.attr('value').replace(/\n/g, '<br />') ); 
		} );
		this.keyup();
	}
	
	else if ( this.is('select') ){
		this.change(function(){
			self = jQuery(this);
			id = self.attr('id');
			target = $('#'+id+'_preview');
			target.html( self.find(':selected').text()  );
		});
		this.change();
	}
	
	
};




});