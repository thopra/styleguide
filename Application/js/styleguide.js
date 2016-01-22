$(function () {

	var Styleguide = function()
	{
		this.init();
	}

	Styleguide.prototype = {

		init: function()
		{
			this.initMenu();
		},

		initMenu: function()
		{
			$('.styleguide__toolbar__menu').sidr({
				name: "styleguideSections"
			});
		},

		initResizableFrames: function()
		{
			$(".styleguide__canvas__frame").resizable({
		    	containment: "parent",
		    	minWidth: 310,
		    	handles: 'e'
		    });
		}
	}

	$(document).ready(function(){
		$(this).data('styleguide', new Styleguide());
	});
});