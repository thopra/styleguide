$(function () {

	var Styleguide = function($element)
	{
		this.$element = $element;
		this.init();

		$(window).resize($.proxy(this.onResize, this));
	}

	Styleguide.prototype = {

		breakpoints: [767,960,1140],
		previews: [],

		init: function()
		{
			this.showLoader();

			this.initMenu();
			this.initViewportControls();
			this.initTabs();

			$('[data-preview="frame"]', this.$element).each($.proxy(function(i, el) { 
			   this.previews.push($(el));
			},this));
			this.initPreviewFrames();

			this.hideLoader();
		},

		onResize: function()
		{
			this.fitFramesToContents();
		},

		initMenu: function()
		{
			$('.styleguide__toolbar__menu').sidr({
				name: "styleguideSections"
			});

			if (this.getBreakpoint() > 0) {
				$.sidr('open', 'styleguideSections');
			}
		},

		initResizableFrames: function()
		{
			$(".styleguide__canvas__frame").resizable({
		    	containment: "parent",
		    	minWidth: 310,
		    	handles: 'e'
		    });
		},

		initViewportControls: function()
		{
			// initialize range slider
		    $( "#styleguideViewportRange" ).slider({
		      value: 100,
		      min: 0,
		      max: 100,
		      slide: function( event, ui ) {
		        $( "#styleguideViewportWidth" ).val( ui.value );
		      }
		    });
		    $( "#styleguideViewportWidth" ).val( $( "#styleguideViewportRange" ).slider( "value" ) );
		},

		initTabs: function()
		{
			$('.nav-tabs a').click(function (e) {
			  e.preventDefault()
			  $(this).tab('show')
			})
		},

		initPreviewFrames: function()
		{
			this.fitFramesToContents();
		},

		// set heights according to contents
		fitFramesToContents: function()
		{
			$(this.previews).each($.proxy(function(i, $el){
				$('iframe', $el).load($.proxy(function(evt){
					console.debug(evt.currentTarget);
					this.fitFrameToContent($(evt.currentTarget));
				},this));
			},this));
		},

		fitFrameToContent: function($frame)
		{
			$frame.height($($frame.contents()).height());
		},

		getBreakpoint: function()
		{
			if ($(window).width() >= this.breakpoints[1] ) {
				return 1;
			}
			if ($(window).width() >= this.breakpoints[2] ) {
				return 2;
			}
			if ($(window).width() >= this.breakpoints[3] ) {
				return 3;
			}

			return 0;
		},

		showLoader: function()
		{
			this.$element.addClass('styleguide--loading');
		},

		hideLoader: function()
		{
			this.$element.removeClass('styleguide--loading');
		}
	}

	$(document).ready(function(){
		$('.styleguide').each(function(i, el) {
			$(el).data('styleguide', new Styleguide($(el)));
		});
	});
});