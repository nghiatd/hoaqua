/*---------------------------------------------------------------------------------------------------
 * jQuery Comet Plugin
 * Copyright (c) 2009 Dunghoangit (Hoang Viet Dung)
 * 
 * Develop by dunghoangit
 * dunghoang.it@gmail.com
 * Please attribute the author if you use it.
 * 
 * Updated on 1:21 AM 6/25/2010
 *---------------------------------------------------------------------------------------------------*/
var isCometStated = false;
var isCometEnabled = true;
(function($) {
    $.fn
			.extend({
			    comet: function(options) {
			    	 var defaults = {
					            params: {}					            
					        };
					       
					        
					var options = $.extend(defaults, options);
			        var connect = function() {
			        	$.post('chat/pub',options.params, function(data) {
			        		if(data){
			        			if(data.message){
			        				$('#last_update').text(data.date);
				        			$.each(data.message,function(k,v){
				        				$('#chat_data').prepend('<div>'+v.date+' <b>'+v.User+'</b>: '+v.message+'</div>');
				        			});
				        			$('#members').html('');
				        			$.each(data.members,function(k,v){
				        				$('#members').prepend('<div><b>'+v+'</b></div>');
				        			});
			        			}
			        			options.params.timestamp = data.timestamp;
			        			connect();
			        		}
			        	}, "json");
			            
			        };

			        $(this).ajaxError(
							function(event, request, settings) {
								alert('error in: ' + settings.url + ' \n'+'error:\n' + xhr.responseText ); 
							}
					);

			        return this.each(function() {
				        if (isCometEnabled) { 
				                setTimeout(connect,1000);
				            }
				            isCometStated = true;
				        });
			    }
			});
})(jQuery);
