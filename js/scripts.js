/*
 * Scripting required for operation
 */

// kick it!
$(function(){
	console.log('onload init');

	$('#viewdisplay').click(function(){
		toggleChart();
	});
	// Reload page on grid size select change
	$('select#gridsize').change(function(e){
    	select_val = $("select#gridsize option:selected").attr('value');
    	window.location.href = select_val;
    });
});


// Simple preset txt strings to use for testing
function presets(type){
	console.log("load preset: " + type);
	switch(type){
		case "p1" :
			txt = "PLACE 4,3,NORTH\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nREPORT";
		break;

		case "p2" :
			txt = "PLACE 1,1,EAST\nMOVE\nMOVE\nLEFT\nMOVE\rMOVE\nLEFT\nMOVE\nRIGHT\nREPORT";
		break;

		case "p3" :
			txt = "PLACE 0,1,NORTH\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nLEFT\nREPORT";
		break;

		case "p4" :
			txt = "PLACE 8,5,WEST\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nMOVE\nLEFT\nREPORT";
		break;

		case "p5" :
			txt = "PLACE 0,1,NORTH\nMOVE\rRIGHT\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nREPORT";
		break;

		case "p6" :
			txt = "PLACE 1,1,NORTH\nMOVE\nMOVE\nRIGHT\nMOVE\nPLACE 3,1,EAST\nMOVE\nLEFT\nMOVE\nREPORT";
		break;

		case "p7" :
			txt = "PLACE 1,2,NORTH\nRIGHT\nMOVE\nLEFT\nMOVE\nMOVE\nRIGHT\nMOVE\nPLACE 2,0,EAST\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nREPORT";
		break;

		case "p8" :
			txt = "PLACE 0,1,NORTH\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nLEFT\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nLEFT\nMOVE\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nLEFT\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nREPORT";
		break;

		case "p9" :
			txt = "PLACE 3,5,NORTH\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nRIGHT\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nREPORT";
		break;

		case "p10" :
			txt = "PLACE 2,7,NORTH\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nRIGHT\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nPLACE 10,2,EAST\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nMOVE\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nPLACE 7,7,WEST\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nMOVE\nMOVE\nREPORT";
		break;
		
		case "p11" :
			txt = "PLACE 15,3,WEST\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nMOVE\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nLEFT\nMOVE\nMOVE\nMOVE\nMOVE\nMOVE\nMOVE\nRIGHT\nMOVE\nLEFT\nMOVE\nMOVE\nRIGHT\nMOVE\nMOVE\nMOVE\nMOVE\nLEFT\nMOVE\nMOVE\nREPORT";
		break;

		case "p12" :
			txt="place 1,2,north\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nright\nmove\nmove\nmove\nmove\nmove\nright\nmove\nleft\nmove\nright\nmove\nleft\nmove\nright\nmove\nmove\nmove\nmove\nright\nmove\nleft\nmove\nright\nmove\nleft\nmove\nright\nmove\nmove\nmove\nmove\nleft\nmove\nleft\nmove\nright\nmove\nleft\nmove\nright\nmove\nleft\nmove\nright\nmove\nleft\nmove\nright\nmove\nleft\nmove\nright\nmove\nleft\nmove\nright\nmove\nplace 11,2,north\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nright\nright\nmove\nleft\nmove\nright\nmove\nmove\nleft\nmove\nright\nmove\nmove\nleft\nmove\nright\nmove\nmove\nleft\nmove\nright\nmove\nmove\nleft\nmove\nright\nmove\nmove\nleft\nmove\nright\nmove\nmove\nleft\nmove\nright\nmove\nmove\nright\nright\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nreport";
		break;

		case "p13" :
			txt="place 9,1,west\nmove\nmove\nmove\nright\nmove\nleft\nmove\nmove\nright\nmove\nleft\nmove\nright\nmove\nleft\nmove\nright\nmove\nmove\nleft\nmove\nright\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nright\nmove\nleft\nmove\nmove\nright\nmove\nleft\nmove\nright\nmove\nleft\nmove\nright\nmove\nmove\nleft\nmove\nright\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nright\nmove\nleft\nmove\nmove\nright\nmove\nleft\nmove\nright\nmove\nleft\nmove\nright\nmove\nmove\nleft\nmove\nright\nmove\nmove\nmove\nmove\nmove\nmove\nmove\nright\nmove\nleft\nmove\nmove\nright\nmove\nleft\nmove\nright\nmove\nleft\nmove\nright\nmove\nmove\nleft\nmove\nright\nmove\nmove\nmove\nplace 6,13,north\nmove\nright\nmove\nright\nmove\nplace 12,13,north\nmove\nright\nmove\nright\nmove\nplace 4,8,east\nmove\nright\nmove\nleft\nmove\nright\nmove\nleft\nmove\nright\nmove\nleft\nmove\nmove\nmove\nmove\nmove\nleft\nmove\nright\nmove\nleft\nmove\nright\nmove\nleft\nmove\nright\nmove\nreport";
		break;

		case "default" :
			txt = "PLACE 0,1,NORTH\nMOVE\nRIGHT\nMOVE\nMOVE\rREPORT";
		break;
	}

	$('#command').html(txt);
	$('.btn-primary').addClass('btn-success');
}

// simple toggle for debug panel display
function show_debug(){
	$('.debug').slideToggle();
}

function show_test_debug(){
	$('.tests .output').slideToggle();
}


// show/hide function for panel display
function toggleChart(){
	if($('.middle')[0]){
		$('.output').removeClass('middle');
		$('form').removeClass('middle');
		$('body').removeClass('open');

		$('button.view').text('Hide Graph');
		$('#panels').val('open');
	}else{
		$('.output').addClass('middle');
		$('form').addClass('middle');

		$('button.view').text('Show Graph');
		$('#panels').val('closed');
	}
}

