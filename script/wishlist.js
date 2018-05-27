
var WISH = {
};

window.onload = function() {
  $('#show-menu-link').click(function(){
    $('#menu-navig').slideToggle('fast');
  });

  $(window).resize(function(e) {
  	if ($(window).width() > 500)
  		$('#menu-navig').css('display', 'flex');
  	else if ($('#menu-navig').css('display') != 'none')
  		$('#menu-navig').css('display', 'none');
  });
}
