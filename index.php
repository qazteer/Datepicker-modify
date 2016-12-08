<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" type="text/css" href="jquery-ui.css">
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript" src="jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="jquery.datepicker.extension.range.min.js"></script>
	<title>Модифицированный Dateoicker</title>
</head>
<body>
	
  	<table class="admintable">
		<tr>
		   <td>
		        <div id="datepicker"></div>
		        <div class="title">Выберите доступные даты</div>
		        <div class="month">
			        <?php 
			        	$monthname = array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');
			        ?>
			        <?php for($i=0; $i<12; $i++): ?>
						<div class="wroap-y">
				        	<input type="checkbox" class="checkbox" name="<?= $i; ?>"><label><?= $monthname[$i]; ?></label><br>
				        	<select class="daysm" multiple disabled name="day[]">
				        		<option value="1">Понедельник</option>
				        		<option value="2">Вторник</option>
				        		<option value="3">Среда</option>
				        		<option value="4">Четверг</option>
				        		<option value="5">Пятница</option>
				        		<option value="6">Суббота</option>
				        		<option value="0">Воскресенье</option>
				        	</select>
				        </div>
			        <?php endfor; ?>
		        </div>
		        <div class="clear"></div>
		        <form action="/" method="post">
                	<div class="inputs"></div>
                	<input type="submit" name="strdate" value="Сохранить">
                </form>
		   </td>
		</tr>
	</table>
	 
</body>
</html>

<?php 
$dates_hover = '';
	//$input = jQuery('.inputs').val();
	if(isset($_POST["strdate"])){
		if(isset($_POST["dynamicDateAdmin"])){
			if(!empty($_POST["dynamicDateAdmin"])){
				$dates_hover = $_POST["dynamicDateAdmin"];
			}
		}else{
			$dates_hover = "";
		}
	}
?>

<script>
	jQuery(document).ready(function(){
		var str_date = "<?php print $dates_hover; ?>";//строка текстовых дат
		var arr_date = [];
		if(str_date != "") arr_date = str_date.split(','); //массив текстовых дат
		var currentYear = new Date().getFullYear(); //текущий год, всё дублируется из года в год!

		if(arr_date.length>0){
			for(var i=0;i<arr_date.length;i++) {
				var str = arr_date[i];
				var strwithout = str.substring(0, str.length - 4);
				arr_date[i] = strwithout + currentYear;
			}
		}

		str_date = arr_date.join(",");

		console.log(arr_date);
		var DatesArray=[];
		if(arr_date.length>0){
			for(var i=0;i<arr_date.length;i++) {
				DatesArray.push(new Date(arr_date[i]));//массив дат
			}
		}

		

	    jQuery( "#datepicker" ).datepicker({
	        inline: true,
	        range: 'multiple', // режим - выбор нескольких дат 
	        changeMonth: true,
    		changeYear: true,
	        onSelect:function(dateText, inst, extensionRange){ 
	                jQuery('.inputs').empty();//отображается после клика
	                jQuery('<input type="text" class="field" name="dynamicDateAdmin" value="'+ extensionRange.datesText + '" />').appendTo('.inputs');
	        }
	    });


	    //отображается сразу после загрузки
	    jQuery('#datepicker').datepicker('setDate', DatesArray);
		jQuery('<input type="text" class="field" name="dynamicDateAdmin" value="'+str_date+'" />').appendTo('.inputs');

		selectMonth(parseInt(currentYear), DatesArray);
		function selectMonth(year, arr){
	        var uniq_date_month = unique(arr);
	        var days = [];
	        
	        
	        jQuery('.checkbox').each(function(i, elem){
	        	var arrcountday = [];
	        	var counday = [];
	        	for(var i=0;i<uniq_date_month.length;i++){
	        		
	        		if(jQuery(this).attr('name') == uniq_date_month[i].getMonth()){
	        			jQuery(this).prop('checked', true);
	        			jQuery(this).parent('div').children('select.daysm').attr("disabled",false);

	        			var date = new Date(year, uniq_date_month[i].getMonth(), 1);
				        
				        while (date.getMonth() === uniq_date_month[i].getMonth()) {
				            days.push(new Date(date));
				            date.setDate(date.getDate() + 1); //текущая дата
   							
   							//заполняем масивы нолями
				            for(var aa=0; aa<7; aa++) counday[aa] = 0;

				            for(var a=0; a<7; a++) arrcountday[a] = 0;
	
							//заполняем масивы значениями определённых дней
				            for(var d=0; d<7; d++){

				           		//количество из базы определённых дней месяца
				           		for(var ds=0; ds<arr.length; ds++){
				           			if(uniq_date_month[i].getMonth() == arr[ds].getMonth() ){
				           				if(d == arr[ds].getDay()){
				           					if(arrcountday[d]){
						            			arrcountday[d] ++;
						            		}else{
						            			arrcountday[d] = 1;
						            		}
				           				}
				           			}
				           		}

				           		//количество всех определённых дней месяца
				           		for(var s=0; s<days.length; s++){ 
				           			if(uniq_date_month[i].getMonth() == days[s].getMonth() ){
				           				if(d == days[s].getDay()){
				           					if(counday[d]){
						            			counday[d] ++;
						            		}else{
						            			counday[d] = 1;
						            		}
				           				}
				           			}
				           		}
				            }
				        }
	        		}
	        	}

				//сравниваем и подсвечиваем дни недели в select
	        	for(var c=0; c<counday.length; c++){
           			if(counday[c] === arrcountday[c]){
           				var h = c;
           				jQuery(this).parent('div').children("select.daysm").children("option[value='" + h + "']").prop("selected", true);
           			}
           		}
			});
 
	        return console.log(days);

		}

		//возвращает массив уникальных месяцов
		function unique(arr) { 
		  var result = [];
		  nextInput:
		    for (var i = 0; i < arr.length; i++) {
		      var str = arr[i]; // для каждого элемента
		      for (var j = 0; j < result.length; j++) { // ищем, был ли он уже?
		        if (result[j].getMonth() == str.getMonth()) continue nextInput; // если да, то следующий
		      }
		      result.push(str);
		    }
		    return result;
		}

		//ВЫБОР МЕСЯЦА
		jQuery('.checkbox').change(function(){ 
		   if(jQuery(this).is(':checked')){
		   		function getDaysInMonth(month, year) {
			        var date = new Date(year, month, 1);
			        var days = [];
			        var dateInput = [];
			        var str_alldate = ''; //строка дат для input
			        var dopsetDate = '';
			        //console.log('month', month, 'date.getMonth()', date.getMonth())
			        while (date.getMonth() === month) {
			           days.push(new Date(date));
			           date.setDate(date.getDate() + 1); //текущая дата
			        }
			        var returnDate = [];

			        var str_val_input = jQuery( ".inputs input" ).val();
			        if(str_val_input != ""){
				        var arr_val_input = str_val_input.split(','); //массив дат с инпута

				        for(var i=0;i<arr_val_input.length;i++){
				        	returnDate.push(new Date(arr_val_input[i])); //массив форматированных дат с инпута
				        }
				    
				        var setDate = returnDate.concat(days); //кантат с массивом дат по месяцу
				        dopsetDate = setDate;

				        for(var i=0;i<setDate.length;i++){ //удаление дублирующих дат
				        	for(var j=0;j<returnDate.length;j++){
				        		if(returnDate[j].getMonth() == month){
					        		if(setDate[i] == returnDate[j]){
					        			dopsetDate.splice(i, 1);
					        			i--;
					        		}
					        	}
				        	}
				        }
				    }else{
				        dopsetDate = days;
				    }
			       
			        for(var i=0;i<dopsetDate.length;i++){ //создание массива для инпута
			           var newdate;
    				   var dt = new Date(dopsetDate[i]);
    				   var n_month =  dt.getMonth() + 1;
			           newdate = n_month + '/' + dt.getDate() + '/' + dt.getFullYear();
			           dateInput.push(newdate);
			        }

			        str_alldate = dateInput.join(); //создание строки из массива для инпута
			        
			        jQuery( "#datepicker" ).datepicker('setDate', dopsetDate);//установить дни месяца
			        
			        //добовление строки в инпут
			        jQuery('.inputs').empty();
	                jQuery('<input type="text" class="field" name="dynamicDateAdmin" value="'+ str_alldate + '" />').appendTo('.inputs');
			        return true;
			    } 
  				
  				jQuery(this).parent('div').children('select.daysm').attr("disabled",false);
			    getDaysInMonth(parseInt(jQuery(this).attr('name')), parseInt(currentYear));
		    }else{
		   		function getDaysInMonthUnset(month, year) {
			        var date = new Date(year, month, 1);
			        var days = [];
			        var dateInput = [];
			        var str_alldate = ''; //строка дат для input
			        var arr_val_input = [];
			        while (date.getMonth() === month) {
			           days.push(new Date(date));
			           date.setDate(date.getDate() + 1); //текущая дата
			        }

			        var returnDate = [];

			        var str_val_input = jQuery( ".inputs input" ).val();
			        var arr_val_input = str_val_input.split(','); //массив дат с инпута

			        for(var i=0;i<arr_val_input.length;i++){
			        	returnDate.push(new Date(arr_val_input[i])); //массив форматированных дат с инпута
			        }

			        var dopsetDate = returnDate;

			        for(var h=0;h<returnDate.length;h++){
			        	if(returnDate[h].getMonth() == month){
			        		dopsetDate.splice(h, 1);
			        		h--;
			        	}
			        }

			        for(var i=0;i<dopsetDate.length;i++){ //создание массива для инпута
			           var newdate;
    				   var dt = new Date(dopsetDate[i]);
    				   var n_month =  dt.getMonth() + 1;
			           newdate = n_month + '/' + dt.getDate() + '/' + dt.getFullYear();
			           dateInput.push(newdate);
			        }

			        str_alldate = dateInput.join(); //создание строки из массива для инпута
			        
			        jQuery( "#datepicker" ).datepicker('setDate', dopsetDate);//установить дни месяца
			        
			        //добовление строки в инпут
			        jQuery('.inputs').empty();
	                jQuery('<input type="text" class="field" name="dynamicDateAdmin" value="'+ str_alldate + '" />').appendTo('.inputs');
			        return true;
			    }
			    jQuery(this).parent('div').children('select.daysm').children('option:selected').prop('selected', false);   
			    jQuery(this).parent('div').children('select.daysm').attr("disabled",true); 
			    getDaysInMonthUnset(parseInt(jQuery(this).attr('name')), parseInt(currentYear));
		    }
		});
		
		//ВЫБОР НЕДЕЛИ
		jQuery('.daysm').change(function(){ 
			
			function setDaysSelect(wdays, month, year) {
				var date = new Date(year, month, 1);
		        var days = [];
		        var dateInput = [];
		        var str_alldate = ''; //строка дат для input
		        var arr_val_input = [];

		        while (date.getMonth() === month) {
		           days.push(new Date(date));
		           date.setDate(date.getDate() + 1); //текущая дата
		        }

		        var returnDate = [];

		        var str_val_input = jQuery( ".inputs input" ).val();
		        var arr_val_input = str_val_input.split(','); //массив дат с инпута

		        for(var i=0;i<arr_val_input.length;i++){
		        	returnDate.push(new Date(arr_val_input[i])); //массив форматированных дат с инпута
		        }

		        var doparr = returnDate;

		        for(var h=0;h<returnDate.length;h++){ //удаляем все даты данного месяца
		        	if(returnDate[h].getMonth() == month){
		        		doparr.splice(h, 1);
		        		h--;
		        	}
		        }

		        if(wdays != null){
			        var weekdays = [];
			        for(var d=0;d<days.length;d++){ //получаем массив с выбранными днями(датами)
			        	for(var w=0;w<wdays.length;w++){
			        		if(days[d].getDay() == wdays[w]){
			        			weekdays.push(days[d]);
			        		}
			        	}	
			        }

			        var dopsetDate = doparr.concat(weekdays);
			    }else{
			    	var dopsetDate = doparr;
			    }

		        for(var i=0;i<dopsetDate.length;i++){ //создание массива для инпута
		           var newdate;
				   var dt = new Date(dopsetDate[i]);
				   var n_month =  dt.getMonth() + 1;
		           newdate = n_month + '/' + dt.getDate() + '/' + dt.getFullYear();
		           dateInput.push(newdate);
		        }

		        str_alldate = dateInput.join(); //создание строки из массива для инпута
		        
		        jQuery( "#datepicker" ).datepicker('setDate', dopsetDate);//установить дни месяца
		        
		        //добовление строки в инпут
		        jQuery('.inputs').empty();
                jQuery('<input type="text" class="field" name="dynamicDateAdmin" value="'+ str_alldate + '" />').appendTo('.inputs');
		        return true;
		    }   
		     
		    setDaysSelect(jQuery(this).val(), parseInt(jQuery(this).prevAll('.checkbox').attr("name")), parseInt(currentYear));
		});

	});
</script>

<style type="text/css">
	.wroap-y{
		float: left;
		margin-right:5px;
	}

	.wroap-y select{
		width:120px;
		height:150px;
	}
	.clear{
		clear: both;
		margin-bottom: 20px;
	}

	.month{
		margin-top: 20px;
	}
	
	.inputs input{
		display: none
	}

	input[type=submit]{
		font: bold 14px Arial,sans-serif;
		background-color: green;
		border: none;
		color:#fff;
		padding: 10px 30px;
	}

	.title{
		font: bold 18px Arial,sans-serif;
		padding-top: 30px;
		color: green;
	}

	.ui-datepicker .ui-datepicker-prev {
	    left: -5px;
	}

	.ui-datepicker .ui-datepicker-next {
	    right: -5px;
	}

</style>