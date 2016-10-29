function ResetSelect(idSelect){
	$("#"+idSelect+" option[value='']").attr('selected', 'selected');
}

function SetValueToSelect(idSelect,valor,bool){
	if(bool== true){
		$("#"+idSelect+" option[value='"+valor+"']").attr("selected",true);
	}else if(bool== false){
		$("#"+idSelect+" option").eq(valor).attr('selected', 'selected');
	}
}

function GetValueSelect(idSelect){
	var value = $("#"+idSelect+" option:selected").val();
	return value;
}

function CleanInput(idCampo){
	$("#"+idCampo).get(0).value = "";
}

function GetValueToInputText(idInputText){
	valor = $('#'+idInputText).val();
	return valor;
}

function SetValueToInputText(idInputText,value){
	$('#'+idInputText).val(value);
}

function SetOnclickToButton(idButton,onclick){
	$("#"+idButton).attr("onclick",onclick);
}

function Disabled(idInput){
	$("#"+idInput).attr("disabled", true);
}

function Enabled(idInput){
	$("#"+idInput).attr("disabled", false);
}

function DisabledReadonly(idInput){
	$("#"+idInput).attr("readonly", true);
}

function EnabledReadonly(idInput){
	$("#"+idInput).attr("readonly", false);
}

function DisabledSelect(idSelect){
	$("#"+idSelect).attr("disabled", true);
}

function EnabledSelect(idSelect){
	$("#"+idSelect).attr("disabled", false);
}

function DisabledBtn(idBtn){
	$("#"+idBtn).removeClass();
	$("#"+idBtn).addClass("button large gray disabledBtn");
	$("#"+idBtn).attr("disabled", true);
}

function EnabledBtn(idBtn,colorBtn){
	$("#"+idBtn).removeClass("button large gray disabledBtn");
	 if(colorBtn != ""){
		$("#"+idBtn).addClass("button large "+colorBtn);
		$("#"+idBtn).attr("disabled", false);
	 }else{
		DisabledBtn(idBtn);
	 }
}

function ShowElement(idElement){
	$("#"+idElement).css("display", "block");
}

function HideElement(idElement){
	$("#"+idElement).css("display", "none");
}

function FadeInElement(idElement){
	$("#"+idElement).fadeIn(500);
}

function FadeOutElement(idElement){
	$("#"+idElement).fadeOut(500);
}




function ScrollWinTop() {
	$('html,body').animate({
		scrollTop: 0
	}, 1000);
}

function Calendar(id) {
		$("#" + id).datepicker({
				dateFormat: 'yy-mm-dd'
		});
}

function CalendarRang(id,id2) {
		$("#" + id).datepicker({
			dateFormat: 'yy-mm-dd',
	    defaultDate: "+1w",
	    changeMonth: true,
	    numberOfMonths: 3,
			minDate: '0',
	    onClose: function( selectedDate ) {
	      $("#" + id2).datepicker( "option", "minDate", selectedDate );
	    }
	  });
	  $("#" + id2).datepicker({
			dateFormat: 'yy-mm-dd',
	    defaultDate: "+1w",
	    changeMonth: true,
	    numberOfMonths: 3,
	    onClose: function( selectedDate ) {
	      $("#" + id).datepicker( "option", "maxDate", selectedDate );
	    }
	  });
}

function GetValueCvr(idCheckbox) {
	 var arr = new Array();
	 $.each($("input[name='"+idCheckbox+"[]']:checked"), function () {
		 arr.push($(this).val());
	 });
	 return arr[0];
}

function ValidateOnlyNumbers(idInput){
	$(document).ready(function(){
	$("#"+idInput).keydown(function(event) {

	   if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39)    {
		   return  true;
	   }
	   else {
			if (event.keyCode < 95) {
			  if (event.keyCode < 48 || event.keyCode > 57) {
					event.preventDefault();
			  }
			}
			else {
				  if (event.keyCode < 96 || event.keyCode > 105) {
					  event.preventDefault();
				  }
			}
		  }
	   });
	});
}

function DeleteHtmlById(idElement){
	$("#"+idElement).remove();
}

function PrintTable(idButton,idArea){
	$("#"+idButton).click(function(){
		$("#"+idArea).printArea();
	});
}

function ExportToExcelTable(idButton,idArea){
	$("#"+idButton).click(function(){
		window.open('data:application/vnd.ms-excel,' + encodeURIComponent($("#"+idArea).html()));
	});
}


function AjaxControlWithFilterLstFunc(ajaxControlConfigDataLst,dataQueryComponentAjax){
		var valueInputCtx = GetValueToInputText(ajaxControlConfigDataLst.idCtx);
		dataQueryComponentAjax.valueSearchField = valueInputCtx;
		var parameters = {
				"ajaxControlConfigDataLst" : ajaxControlConfigDataLst,
				"dataQueryComponentAjax" : dataQueryComponentAjax
		};
		$.ajax({
				data:  parameters,
				url:   ajaxControlConfigDataLst.loadUrl,
				type:  'post',
				beforeSend: function () {
						$("#"+ajaxControlConfigDataLst.idContainerGeneral).html('<img src="../../view/imgs/loader2.gif" width="35" height="35"/>').show();
				},
				success:  function (response) {
						$("#"+ajaxControlConfigDataLst.idContainerGeneral).html(response);
				}
		});
}

function AjaxControlTbl(ajaxControlConfigDataTbl){
		var parameters = {
				"ajaxControlConfigDataTbl" : ajaxControlConfigDataTbl
		};
		$.ajax({
				data:  parameters,
				url:   ajaxControlConfigDataTbl.loadUrl,
				type:  'post',
				beforeSend: function () {
						$("#"+ajaxControlConfigDataTbl.idContainerTbl).html('<img src="../../view/imgs/loader2.gif" width="35" height="35"/>').show();
				},
				success:  function (response) {
						$("#"+ajaxControlConfigDataTbl.idContainerTbl).html(response);
				}
		});
}

function AjaxExcelTbl(ajaxControlConfigDataTbl){
		var parameters = {
				"ajaxControlConfigDataTbl" : ajaxControlConfigDataTbl
		};
		$.ajax({
				data:  parameters,
				url:   ajaxControlConfigDataTbl.loadUrl,
				type:  'post',
				beforeSend: function () {
						$("#"+ajaxControlConfigDataTbl.idContainerTbl).html('<img src="../../view/imgs/loader2.gif" width="35" height="35"/>').show();
				},
				success:  function (response) {
						$("#"+ajaxControlConfigDataTbl.idContainerTbl).html(response);
				}
		});
}
