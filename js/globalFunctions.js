$(document).ready(function(){
	
	//------------------------------------------------------ DROP DOWN ------------//
	$("ul.subnav").parent().append("<span></span>");
	$("ul.topnav li span, .menu_head").click(function() { 
		
		$(this).parent().find("ul.subnav").slideDown('fast');
		
		$(this).parent().hover(function() {
		}, function(){
			$(this).parent().find("ul.subnav").slideUp('fast');

		});
		
		}).hover(function() {
			
			$(this).addClass("subhover");

		}, function(){ 

			$(this).removeClass("subhover"); 
			
	});
	//------------------------------------------------------ FAQ TOGGLE ------------//
	
	$('ul.all-faq li h4').toggle(function(){
		
			$(this).removeClass('closed-faq').addClass('open-faq');
			$(this).parent().find('p').fadeIn();
		
	},function(){
		$(this).removeClass('open-faq').addClass('closed-faq');
		$(this).parent().find('p').fadeOut();
	});
	
	
	//------------------------------------------------------ PROJECT THUMBNAIL HOVER ------------//
	$("#home .blogTitle_box ul li").hover(function() {
		$(this).find('.hoverlay').fadeIn(300);
		
	},function(){
		$(this).find('.hoverlay').fadeOut(300);
		
	});
	
	//------------------------------------------------------ TIPSY PLUGIN TOOL TIPS ------------//
	$('#example-1').tipsy();
	 
	 $('#north').tipsy({gravity: 'n'});
	 $('#south').tipsy({gravity: 's'});
	 $('#east').tipsy({gravity: 'e'});
	 $('#west').tipsy({gravity: 'w'});
	 
	 $('#auto-gravity').tipsy({gravity: $.fn.tipsy.autoNS});
	 
	 $('.fade').tipsy({fade: true});
	 
	 $('#example-custom-attribute').tipsy({title: 'id'});
	 $('#example-callback').tipsy({title: function() { return this.getAttribute('original-title').toUpperCase(); } });
	 $('#example-fallback').tipsy({fallback: "?" });
	 
	 $('#example-html').tipsy({html: true });
	
	 // on page load resize
	 equalHeight($(".col"));
	equalHeight2($(".inner-col"));
	
	
	$('a, div, li, h1, h2, h3, h4, h5, h6, button').click(function(){
		equalHeight($(".col"));
	});
	
});

function equalHeight(group) {
   tallest = 0;
   group.each(function() {
      thisHeight = $(this).height();
	  //thisHeight = $(this).height();
      if(thisHeight > tallest) {
         tallest = thisHeight;
      }
   });
   group.css('min-height',tallest);
}
function equalHeight2(group) {
   tallest = 0;
   group.each(function() {
      thisHeight = $(this).height();
	  //thisHeight = $(this).height();
      if(thisHeight > tallest) {
         tallest = thisHeight;
      }
   });
   	group.attr('style', 'height:'+tallest+'px !important');
}
