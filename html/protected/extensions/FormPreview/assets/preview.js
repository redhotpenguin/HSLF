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


function force_refresh() {
    var inputs = $('#candidate-form :input');
    inputs.each(function(index,field){
      $('#'+field.id).add_preview();
    });    
}

var check_form_result = setInterval(force_refresh, 1000);

});

