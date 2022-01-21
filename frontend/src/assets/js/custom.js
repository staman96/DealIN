jQuery(document).ready(function(){
	jQuery('.close').click(function(){
		jQuery('.create-box').hide();
	});
	jQuery('.create-message').click(function(){
		jQuery('.create-box').css('display', 'flex');
	});

	jQuery('.filters a').click(function(){
		var data = jQuery(this).data('action');
		jQuery('.filters a').removeClass('current');
		jQuery(this).addClass('current');
		jQuery('.overflow-area .inbox, .overflow-area .sent').hide();
		jQuery('.overflow-area .' + data).show();
	});
	
});
jQuery(document).ready(function()
{
	"use strict";

	/* 

	1. Vars and Inits

	*/

	var menu = jQuery('.menu');
	var burger = jQuery('.hamburger');
	var menuActive = false;

	jQuery(window).on('resize', function()
	{
		setTimeout(function()
		{
			jQuery(window).trigger('resize.px.parallax');
		}, 375);
	});

	initMenu();
	// initHomeSlider();
	initIsotope();

	/* 

	2. Init Menu

	*/

	function initMenu()
	{
		if(menu.length)
		{
			if(jQuery('.hamburger').length)
			{
				burger.on('click', function()
				{
					if(menuActive)
					{
						closeMenu();
					}
					else
					{
						openMenu();

						jQuery(document).one('click', function cls(e)
						{
							if(jQuery(e.target).hasClass('menu_mm'))
							{
								jQuery(document).one('click', cls);
							}
							else
							{
								closeMenu();
							}
						});
					}
				});
			}
		}
	}

	function openMenu()
	{
		menu.addClass('active');
		menuActive = true;
	}

	function closeMenu()
	{
		menu.removeClass('active');
		menuActive = false;
	}

	/* 

	3. Init Home SLider

	*/

	function initHomeSlider()
	{
		if(jQuery('.home_slider').length)
		{
			var homeSlider = jQuery('.home_slider');
			homeSlider.owlCarousel(
			{
				items:1,
				loop:true,
				autoplay:false,
				smartSpeed:1200
			});

			if(jQuery('.home_slider_prev').length)
			{
				var prev = jQuery('.home_slider_prev');
				prev.on('click', function()
				{
					homeSlider.trigger('prev.owl.carousel');
				});
			}

			if(jQuery('.home_slider_next').length)
			{
				var next = jQuery('.home_slider_next');
				next.on('click', function()
				{
					homeSlider.trigger('next.owl.carousel');
				});
			}

		}
	}

	/* 

	4. Init Isotope Filtering

	*/

    function initIsotope()
    {
    	if(jQuery('.grid').length)
    	{
    		// jQuery('.grid').isotope({
	  		// 	itemSelector: '.grid-item',
	  		// 	percentPosition: true,
	  		// 	masonry:
	  		// 	{
			// 	    horizontalOrder: true
			//   	}
	        // });

	        if(jQuery('.portfolio_category').length)
	    	{
	    		jQuery('.portfolio_category').click(function()
		    	{
			        jQuery('.portfolio_category.active').removeClass('active');
			        jQuery(this).addClass('active');
			 
			        var selector = jQuery(this).attr('data-filter');
			        jQuery('.portfolio_grid').isotope({
			            filter: selector,
			            animationOptions: {
			                duration: 750,
			                easing: 'linear',
			                queue: false
			            }
			        });

			         return false;
			    });
	    	}
    	}
    }

});
