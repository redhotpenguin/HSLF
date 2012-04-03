jQuery(document).ready(function($) {

    jQuery.fn.add_preview = function(filter) {
        var self;
        if(this.is('input') || this.is('textarea')) {           
            this.keyup( function (e){   
                self = jQuery(this);
                id = self.attr('id');
                target = $('#'+id+'_preview');
                content = self.attr('value');
                        
                if(filter != undefined){
                    content = filter(content);
                }
                        
                target.html( content  ); 
            } );
            this.keyup();
        }
	
        else if ( this.is('select') ){
            this.change(function(){
                self = jQuery(this);
                id = self.attr('id');
                target = $('#'+id+'_preview');
                content =  self.find(':selected').text();
                        
                if(filter != undefined){
                    content = filter(content);
                }
                        
                target.html(content);
            });
            this.change();
        }
    };
});