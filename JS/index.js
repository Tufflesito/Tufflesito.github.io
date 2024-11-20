(function($) { 
	$(function() { 
	  $('#navbar-toggle').click(function() {
		$('nav ul').slideToggle();
	  });
  
	  $('#navbar-toggle').on('click', function() {
		this.classList.toggle('active');
	  });
  
	  $('nav ul li a:not(:only-child)').click(function(e) {
		$(this).siblings('.navbar-dropdown').slideToggle("slow");
		$('.navbar-dropdown').not($(this).siblings()).hide("slow");
		e.stopPropagation();
	  });
  
	  $('html').click(function() {
		$('.navbar-dropdown').hide();
	  });

}); 
})(jQuery);