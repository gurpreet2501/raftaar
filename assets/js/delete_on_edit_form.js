jQuery(function($){
    var c = '';
	c = $('body').attr('class');
    var url = window.location.href;
   	var segments = url.split( '/' );
	var recordId = segments[8];
    
	if(c.trim() == 'edit_spouts')
		$('form .buttons-box').before('<div class="edit-form-del-btn"><a href="http://www.spout.com/public/admin/data/delete/'+parseInt(recordId)+'" class="form-button-box edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary fancybox iframe" role="button"><input type="button" class="ui-input-button edit-form-del" value="Delete"></a></div>');

});

