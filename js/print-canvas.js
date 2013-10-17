function printCanvas()  
{  
    var dataUrl = document.getElementById('dressup').toDataURL(); //attempt to save base64 string to server using this var  
    var windowContent = '<!DOCTYPE html>';
    windowContent += '<html>'
    windowContent += '<head><title>Actitud Real Bratz</title></head>';
    windowContent += '<body>'
    windowContent += '<img src="' + dataUrl + '">';
    windowContent += '</body>';
    windowContent += '</html>';
    var printWin = window.open('','','width=466,height=664');
    printWin.document.open();
    printWin.document.write(windowContent);
    printWin.document.close();
    printWin.focus();
    printWin.print();
    printWin.close();
}

function instrucciones() { window.open( "instrucciones.html", "Instrucciones", "status=1, height=400, width=1024, resizable=0" )
}

	$( "#popup" ).click(function() {	
window.open( "instrucciones.html", "Instrucciones", "status=1, height=700, width=1024, resizable=0" )
	});