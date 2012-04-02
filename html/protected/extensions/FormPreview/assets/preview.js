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
                        
                target.html( content.replace(/\n/g, '<br />') ); 
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


    function force_refresh() {
        inputs = $('#candidate-form :input');
        inputs.each(function(index,field){
            $('#'+field.id).add_preview();
        });    
    }

 //   var check_form_result = setInterval(force_refresh, 1000);

});

