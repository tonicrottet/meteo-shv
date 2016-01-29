/*
*
* azoom Menu Plugin
* @author - Toni Crottet - www.azoom.ch - toni@azoom.ch - copyright
*
* Version 1.0 
*
* Usage:
* modus: hover, for mouseover
* modus: touch, for clickable menu used for tablets and smartphones 
*
*/

;(function( $ ){
    $.fn.azoommenu = function(o){
    
        var o = $.extend({ 
              'modus' : 'hover',
			  'animation' : 'normal' }
              ,o);

		var animationopen;
		var animationclose;
		
        var menu = {
    
            hover : function(){
				
				var $animation = o.direction == 'updown' ? 'height' : 'width';

                //$this.find('>ul').show();

                // Behavior
                $this.find('>ul li').hover(function() {
                	var timeout = $(this).data('timeout');
                 
				 	if(timeout) clearTimeout(timeout);
                	
					$(this).children("ul").animate(animationopen);
                 }, function() {
                 	
					$(this).data('timeout', setTimeout($.proxy(function() {
                 		$(this).find("ul").animate(animationclose);
                	}, this), 300));
               	});
            },
			
			touch : function () {
				// only attach to the ones that have subitems
				$this.children('ul').children('li').children('ul').parent().click(function (event) {
					
					var $$ = $(this);
					
					if(!$$.children('ul').data('open')) {
						// prevent link beeing followed
						event.preventDefault();
						event.stopPropagation();
					} else {
						// menu is already open, so let the link follow
						return true;	
					}
					
					// close all others upfront
					$this.children('ul').find('ul').animate(animationclose).data('open', false);
					
					$$.children('ul').animate(animationopen).data('open', true);

					// when click somewhere else in the window, close open one!!
					$(window).bind('click touchend',function () {
						// defer closing, prevent chasing events
						setTimeout($.proxy(function() {
							$$.children('ul').animate(animationclose).data('open', false);
						}, this), 1000);
						
						$(window).unbind('click touchend');
					});
				});
			}
        };
		
		var $this = $(this);
		
        return this.each(function() {
			
			switch (o.animation)
			{
				case 'normal':
					animationopen = {opacity:'show', height:'show'};
					animationclose = {opacity:'hide', height:'hide'};
					break;
				  
				case 'lefttoright':
					animationopen = {opacity:'show', width:'show'};
					animationclose = {opacity:'hide', width:'hide'};
					break;
					
				case 'nodirection':
					animationopen = {opacity:'show'};
					animationclose = {opacity:'hide'};
					break;

				default:
					animationopen = {opacity:'show', height:'show'};
					animationclose = {opacity:'hide', height:'hide'};
					break;
			}	
			
			menu[o.modus]();
        });
      
    };
})( jQuery );