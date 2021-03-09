//*****************************************
//	   Created By: Aaron Skinner
//****************************************/

var devnetPopups = 0;
//AJAX Object.
function devnetXMLHTTP(){
	if( window.XMLHttpRequest ){
		var xmlhttp = new XMLHttpRequest();
	} else {
		var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return xmlhttp;
}
var userOnlineXML = devnetXMLHTTP();
//Checks for taken usernames/emails in the registration page.
function devnetCheckUserField(field, str){
	var xmlReq = devnetXMLHTTP();
	xmlReq.open("GET", "userfchk.php?f="+field+"&s="+str, false);
	xmlReq.send();
	return xmlReq.responseText;
}

function init(){
	window.setInterval(function(){
		if( userOnlineXML ){
			userOnlineXML.open("GET", "online.php", true);
			userOnlineXML.send();
		}
	}, 120000);
}

function devnetAJAXPost(formId){
    var elem = document.getElementById(formId).elements;
    var params = "";
    url = document.getElementById(formId).action;
    for(var i = 0; i < elem.length; i++){
        if (elem[i].tagName == "SELECT"){
            params += elem[i].name + "=" + encodeURIComponent(elem[i].options[elem[i].selectedIndex].value) + "&";
        }else{
			if( !(elem[i].type == "checkbox" && !elem[i].checked) ){
				params += elem[i].name + "=" + encodeURIComponent(elem[i].value) + "&";
			}
        }
    }
	var xmlhttp = devnetXMLHTTP();
    xmlhttp.open("POST",url,false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.setRequestHeader("Content-length", params.length);
    xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(params);
    return xmlhttp.responseText;    
}

function createPopup(title, text){
	devnetPopups = devnetPopups + 1;
	var overlay = document.getElementById("popupSpace");
	var newPopup = "<div class='popup' id='popup["+devnetPopups+"]'>" +
						"<div class='sideheader'><div class='midheader'>"+title+"</div><div id='closePopup["+devnetPopups+"]' class='miniButton' onclick='removePopup("+devnetPopups+");'><center>x</center></div></div><div class='textbox'>" + text +
					"</div></div>";
	if( devnetPopups == 1 ){
		overlay.style.display = 'inline';
	}
	overlay.innerHTML = overlay.innerHTML + newPopup;
	var curPopup = document.getElementById('popup['+ devnetPopups +']');
	curPopup['z-index'] = 9000 + devnetPopups;
}

function removePopup(id){
	var curDiv = document.getElementById('popup['+id+']');
	curDiv.parentNode.removeChild(curDiv);
	devnetPopups = devnetPopups - 1;
	if( devnetPopups < 0 ){
		devnetPopups = 0;
	}
	var overlay = document.getElementById("popupSpace");
	if( devnetPopups == 0 ){
		overlay.style.display = 'none'
	}
}

function createErrorArrayPopup(title, array, tcol, bcol){
	var tcol = tcol || "#DBDBDB";
	var bcol = bcol || "#C4C4C4";
	var curCol = tcol;
	var outPut = "<table style='border: #C4C4C4 solid 1px; width:100%; font-weight:normal; font-size:12px; color:#464646;' cellspacing=0 cellpadding=2><tr><th style='background-color:"+bcol+"; font-weight:normal; border-bottom:#CC0000 solid 1px; color:#464646;'>Please correct the following before you continue:</th></tr>";
		for( var i = 0; i < array.length; i++ ){
			curCol = i % 2 == 0 ? tcol : bcol;
			outPut = outPut + "<tr><td style='background-color:"+curCol+";'>"+array[i]+"</td></tr>";
		}
	outPut = outPut + "</table>";
	createPopup(title, outPut);
}



function devnetLogin(){
	var loginResult = devnetAJAXPost('login_user');
	var logBox = document.getElementById('loginarea');
	if( loginResult == 1 ){
	}else{
	}
}

function inputAlphaNumeric( elem ) {
	var numberExp1 = /^[0-9]+$/;
	var lettersExp1 = /^[a-zA-Z]+$/;
	var alphaExp1 = /^[0-9a-zA-Z]+$/;
	var inpute = elem.value;
	if( inpute.match(numberExp1) || inpute.match(lettersExp1) || inpute.match(alphaExp1) ) {
		return true;
	}
	return false;
}

function inputEmail(elem) {
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
	if( elem.value.match(emailExp) ) {
		return true;
	}
	return false;
}

function registrationValidation(){
	var Fields = new Array();
	var Errors = new Array();
	Fields["unamer"] = document.getElementById("unamer");
	Fields["passr"] = document.getElementById("passr");
	Fields["pass2r"] = document.getElementById("pass2r");
	Fields["emailr"] = document.getElementById("emailr");
	Fields["dob"] = new Array();
	Fields["dob"]["dob1"] = document.getElementById("dob1"); // Day
	Fields["dob"]["dob2"] = document.getElementById("dob2"); // Month
	Fields["dob"]["dob3"] = document.getElementById("dob3"); // Year
	Fields["sex"] = document.getElementById("sex");
	Fields["country"] = document.getElementById("country");
	Fields["reff"] = document.getElementById("reff");
	Fields["tos"] = document.getElementById("tos");
	//Fields["captcha"] = document.getElementById("captcha");
	
	//First we'll do the easy stuff like ensure compulsory stuff isn't empty.
	if( inputAlphaNumeric(Fields["unamer"]) && Fields["unamer"].value.length > 4 ) {
		if( devnetCheckUserField("uname", Fields["unamer"].value) == 1 ){
			Errors.push("Username has already been taken.");
		}
	}else{
		Errors.push("Username can only be letters or numbers and more than 4 characters.");
	}
	if( Fields["passr"].value == Fields["pass2r"].value ){
		if( Fields["passr"].value.length < 6 ){
			Errors.push("Password must be 6 or more characters.");
		}
	}else{
		Errors.push("Passwords do not match.");
	}
	if( inputEmail(Fields["emailr"]) ) {
		if( devnetCheckUserField("email", Fields["emailr"].value) == 1 ){
			Errors.push("E-mail has already been taken.");
		}
	}else{
		Errors.push("E-mail is not valid.");
	}
	if( !Fields["tos"].checked ){
		Errors.push("You have not accepted our terms of service.");
	}
	
	if( Errors.length > 0 ){
		// Show error popup;
		createErrorArrayPopup("There's a problem with your details.", Errors);
		return false;
	}
	
	//Actually register the user here.
	var AjaxResult = devnetAJAXPost('register_user');
	if( AjaxResult == 1){
		var regoForm = document.getElementById('registration_area');
		regoForm.innerHTML = '<div class="sideheader"><div class="midheader">Registration Successfull</div></div>' +
							'<center>Please check your e-mail for account validation info.</center>';
	}else{
		var regoForm = document.getElementById('registration_area');
		regoForm.innerHTML = '<div class="sideheader"><div class="midheader">An error has occured</div></div>' +
							'<center>Please try again or contact an administrator.</center>';
	}
}
function minimizeBoxes(elem){
	elem.style.height = '22px';
	elem.style['padding-bottom'] = '0px';
}

function maximizeBoxes(elem){
	elem.style.height = 'auto';
	elem.style['padding-bottom'] = '5px';
}
function miniMax(elem, btn){
	if( !elem.hasMini ){
		minimizeBoxes(elem);
		btn.innerHTML = "<center>+</center>";
		elem.hasMini = true;
		return;
	}
	maximizeBoxes(elem);
	elem.hasMini = false;
	btn.innerHTML = "<center>-</center>";
}

	