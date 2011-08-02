/*

jQuery history awesomeness added by rad master Doug Neiner
http://pixelgraphics.us

*/

jQuery(document).ready(function($){

	$('li#toplevel_page_upthemes a').live('click', function(){
		scroll(0,0);
	});
	
	$('li#toplevel_page_upthemes li.wp-first-item').remove();
	
	$('textarea.click-copy').click(function(){
		$(this).select();
	});

	$nav = $("#up_nav");
	$tabber = $('#tabber').children().hide().end();
	
	$.History.bind( function(path){
		path = '#' + path.substr(1);
		change_tab( path );
	});
	
	function change_tab( id ){
		var $a     = $nav.find('a[href*=' + id + ']'),
			$t     = $tabber.find( id ),
			clicked_tab_ref_height;
		
		$('form#theme-options').attr('action', '#/'+id.replace("#",""));
		
		if(id == '#import-export'){
			$('button#up_save').fadeOut();
		}
		else{$('button#up_save').fadeIn();}
		
		if(!$t.is(':visible')){
			$nav.find('li.selected').removeClass('selected');
			$a.closest('li').andSelf().addClass('selected');
			
			clicked_tab_ref_height = $t.css({position: 'absolute', opacity: 0, display: 'block'}).height();
			$t.css({position: 'relative', opacity: 1, display: 'none'});
			
			var fadeOut = function(e){
				$tabber.stop().animate({
					height: clicked_tab_ref_height
				},400,function(){
					$(this).height('auto');
			//Callback after new tab content's height animation
			$t.fadeTo(500, 1);
				});
			}
			
			var $visible = $tabber.children(':visible');
			if($visible.length) {
				$tabber.height( $tabber.height() );
				$visible.fadeOut(400, fadeOut);
			} else {
				fadeOut();
			}
		}
	}
	
	$nav.find('li a').click(function(evt){
		var id = $(this).attr('href').substr(1);
		$.History.setHash('/' + id);
		evt.preventDefault();
	})

	// var hashSelector = 'a[href*=' + document.location.hash + ']';
	if(!document.location.hash) {
		$('#up_nav li:first a').click();
		$('html').scrollTop(0);
	}
	
	var buttonOffset = $('#button-zone').offset();		
	if(buttonOffset)
		var originalOffest = buttonOffset.top;
	
	var Sticky = function( $obj, opts ){
		  
	   $(window).scroll( 
		  function(e){
			 Sticky.onScroll(e, $obj, opts );
		  });
	   
	}
	Sticky.onScroll = function( e, $o, opts ){
	   
	   var iScrollTop = $(window).scrollTop();
	   var sClass = "sticky";
	   
	   //set original data
	   if( !$o.data(sClass) ){
		  $o.data(sClass, {css:{position:$o.css('position'),top:$o.css('top')}, offset:$o.offset()} );
	   }
	   var oOrig = $o.data(sClass);
	   var bIsSticky = $o.hasClass(sClass);
	   
	   if( iScrollTop > oOrig.offset.top && !bIsSticky ){
		  $o.css({position:'fixed',top:0}).addClass(sClass);
	   }else if(iScrollTop < oOrig.offset.top && bIsSticky){
		  $o.css(oOrig.css).removeClass(sClass);
	   }   
	   
	}
	Sticky( $('#button-zone') );
	
	$('#upthemes_framework input, #upthemes_framework select,#upthemes_framework textarea[class!=click-copy][class!=up_import_code]').live('change', function(e){
	
		$('#button-zone').animate({ 
			backgroundColor: '#555',
			borderLeftColor: '#555',
			borderRightColor: '#555'
		});
		$('#button-zone button').addClass('save-me-fool');
		$('.formState').fadeIn( 400 );
	
	});
	
	$colorpicker_inputs = $('input.popup-colorpicker');
	
	$colorpicker_inputs.each(
		function(){
		   var $input = $(this);
		   var sIdSelector = "#" + $(this).attr('id') + "picker";
		   var oFarb = $.farbtastic(
			  sIdSelector,
			  function( color ){		             
				 
				 $input.css({
				backgroundColor: color,
				color: oFarb.hsl[2] > 0.5 ? '#000' : '#fff'
			  }).val( color );
			  
			  
			  if( oFarb.bound == true ){
				 $input.change();
			  }else{
				 oFarb.bound = true;
			  }
			  }
		   );
		   oFarb.setColor( $input.val() );

		}
	);
	
	$colorpicker_inputs.each(function(e){
		$(this).parent().find('.popup-guy').hide();
	});

	
	$colorpicker_inputs.live('focus',function(e){
		$(this).parent().find('.popup-guy').show();
		$(this).parents('li').css({
			position : 'relative',
			zIndex : '9999'
		})
		$('#tabber').css({overflow:'visible'});
	});

	$colorpicker_inputs.live('blur',function(e){
		$(this).parent().find('.popup-guy').hide();
		$(this).parents('li').css({
			zIndex : '0'
		})
	});
})