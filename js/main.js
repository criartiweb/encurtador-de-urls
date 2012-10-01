// JavaScript Document

function validateNew() {
	
	if ( $('#url').val() == '' ) {
		$('#url').attr('value','Por favor informe uma URL válida');
		$('#url').css('border-color','#990000');
		return false;
	} else if ( $('#url').val() == 'Por favor informe uma URL válida' ) {
		$('#url').attr('value','Por favor informe uma URL válida');
		$('#url').css('border-color','#990000');
		return false;
	} else if ( $('#url').val().substring(0,7) != 'http://' && $('#url').val().substring(0,8) != 'https://' ) {
		$('#url').css('border-color','#990000');
		return false;
	} else {
		$('#url').css('border-color','#999999');
		$('#frmNew').attr('action','?url=' + $('#url').val());
		$('#url').attr('value','');
	}
	
}