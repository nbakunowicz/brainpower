/**
 * This is the js file imported onto every page.
 */

var textCache = {};

window.onresize = function(){
	removeSmallNavBar();
	removeSaveText();
};

function removeSmallNavBar() {
	if ($("#isXs").css('display') != "block"){
		$("#topNavBar").removeClass("in");
	}
}

function removeSaveText() {
	if ($("#isXs").css('display') == "block"){
		document.getElementById("footerEdit").innerHTML = "";
	}
}

//This function adds "active" to an element's class
function highlightTab(elementId){
	$("." + elementId).addClass("active");
}

function editable(buttonEl){
	var textId;
	
	if (buttonEl.innerHTML != ""){
		saveTextChanges();
	}

	$(".editable").each(function() {
		
		if (this.contentEditable == "true"){			
			
			this.contentEditable = "false";
			$( this.parentNode ).removeClass("hrefDisabled");
			buttonEl.innerHTML = "";
			$(".footer").removeClass("editModeBackground");
		}
		else {
			this.contentEditable = "true";
			$( this.parentNode ).addClass("hrefDisabled");
			buttonEl.innerHTML = ($("#isXs").css('display') != "block") ? " Save" : "";
			$(".footer").addClass("editModeBackground");
			textCache[this.id] = this.innerHTML;
		}
	});
}

function saveTextChanges(){
    var data = {};
    var changedText = [];
    var textId;
    
	$(".editable").each(function() {

		if (textCache[this.id] != this.innerHTML){
			var saveItem = {};
			
			if (this.innerHTML == null || this.innerHTML == ""){
				this.innerHTML = "[empty]"
			}

			saveItem[(this.id).replace("text", "")] = this.innerHTML;
			changedText.push(saveItem);
		}
	});

	if (changedText.length > 0){
	    data["action"] = "textSave";
	    data["changedText"] = changedText;

		$.ajax({
			type: 'POST',
			url: '/php/databaseAction.php',
			data: data,
			async: false,
		});
	}
}