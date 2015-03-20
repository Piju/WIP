(function($){
	$(document).ready(function(){
		$(".marker > a").mouseover(function(){

			$("body").append('<div class="tooltip"></div>');
			var infobulle=$(".tooltip:last");
			infobulle.append( $(this).next('.point').html() );

			var top=$(this).offset().top+$(this).height();
			var left=$(this).offset().left+$(this).width();

			infobulle.css({
			        left:left,
			        top:top-10,
			        opacity:0
			    });
			    infobulle.animate({
			        opacity:1
			    });
		});

		$(".marker > a").mouseout(function(){
		    var infobulle = $(".tooltip:last");
		    infobulle.animate({
		        top:infobulle.offset().top,
		        opacity:0
		    },
		    500,
		    "linear",
		    function(){
		        infobulle.remove();
		    });
		});
	});
})(jQuery);