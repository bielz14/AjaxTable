<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Ajax table</title>
    <meta charset="utf-8">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.js"></script>
    <script   type="text/javascript" src="jquery-validation/dist/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css"></script>
    <link rel="stylesheet" href="css/styles.css"></script>
  </head>
  <body>
    <div class="container" style="padding-top: 3%">
       <div class="form">
        <form method="POST" id="form" name="form" action="javascript:void(null);" onclick="call()">
          <div class="control-group">
            <label class="control-label" for="inputFirstName">First name</label>
            <div class="controls">
              <input type="text" id="inputFirstName" name="inputFirstName" class="inputFirstName" value="" placeholder="">
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="inputSecondName">Second name</label>
            <div class="controls">
              <input type="text" id="inputSecondName" name="inputSecondName" class="inputSecondtName" value="" placeholder="">
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="inputEmail">E-mail</label>
            <div class="controls">
              <input type="text" id="inputEmail" name="inputEmail" class="inputEmail" value="" placeholder="">
            </div>
          </div>

          <div class="control-group" style="margin-top: 7%">
            <div class="controls"> 
              <button type="submit" id="upload" value="upload" name="upload" class="btn btn-warning">Upload</button>
              <button type="submit" id="update" value="update" name="update" class="btn btn-warning">Update</button>
              <button type="submit" id="delete" value="delete" name="delete" class="btn btn-warning">Delete</button> 
              <input type="text" id="id_row" name="id_row" class="id_row" value="" placeholder="">
            </div>
          </div>

        </form>

      </div>

      <div class="table">
        <table>
          <tbody>
            <tr>
              <th>First name</th>
              <th>Second name</th>
              <th>E-mail</th>
            </tr>
              <?php 
                require "DbConnect.php";

                $obj_db = new DbConnect('localhost', 'testing', 'root',   '1111');

                $connect = $obj_db->getConnection();

                $query = "SELECT * FROM `data`";

                //выполняем запрос для получения данных с таблици
                $result_query = mysqli_query($connect, $query);

                $rows = mysqli_num_rows($result_query); // количество полученных строк

                if($rows>1){ 
	                for ($i = 1 ; $i < $rows+1 ; ++$i)
	                {
	                    $row = mysqli_fetch_row($result_query);
	                    echo "<tr id=$row[0] name='str_$i'>";
	                        for ($j = 1 ; $j < 4 ; ++$j) echo "<td>$row[$j]</td>";
	                    echo "</tr>";
	                }
	            }else if($rows==1){  
		                $row = mysqli_fetch_row($result_query); 
		                echo "<tr id=$row[0] name='str_1'>";
		                for ($j = 1 ; $j < 4 ; ++$j) echo "<td>$row[$j]</td>";
		                echo "</tr>";
		        }

              ?>

          </tbody>
        </table>
      </div>

    </div> 
  </body>
</html>

<script type="text/javascript" language="javascript">

	var id_row = null; 
	var num_row = null;
	var button_click = 0;
	var domsg = false;

	$("body").on("click", "table tr", function(){  

		id_row = this.id;
		$("#id_row").val(id_row);

   		//регулярным выражением вытаскиваем из name только числовое значение, которое будет применятся в выводе alert-ом сообщения о придупреждении удаления строки с этим порядковым номером 
   		num_row = $(this).attr("name").match(/\d+/i); 

	});

  	$("#upload").on("click", function(){ 
  			button_click = 1; 
  		 });

  	$("#update").on("click", function(){ 
  			domsg = true;
  			button_click = 2; 
  	});

  	$("#delete").on("click", function(){ 	
  			domsg = true;
  			button_click = 3; 
  	});

function call(){ 

       var inspect = false;
       // Инициализация плагина на форме
       // Форма имеет атрибут "registration"
       if (button_click<3) {

            $("form[name='form']").validate({

              errorElement: "div",

              // Правила проверки полей
              rules: {
                // Ключ с левой стороны это название полей формы.
                // Правила валидации находятся с правой стороны
                inputFirstName: "required",
                inputSecondName: "required",
                inputEmail: {
                  required: true,
                  // Отдельное правило для проверки email
                  email: true
                }
              },
              // Установка сообщений об ошибке
              messages: {
                inputFirstName: "Please enter your firstname",
                inputSecondName: "Please enter your secondname",
                inputEmail: "Please enter a valid email address",
              },
              submitHandler: function(form) { 

              	if(button_click==2){

              		if(domsg){
			        	if(num_row!=null)inspect = confirm('Are you sure you want to update the data in a row №'+num_row+'?');
				        else {alert('Click on the line you want to update');}
			    	}

			        domsg = false;
              	}

                var msg   = $('#form').serialize(); 
                $.ajax({
                  type: 'POST',
                  url: 'upload.php',
                  data: msg,
                  success: function(data) {  

                  	var obj_data = ','+data.match(/=> .*/g).toString(); 

	                var result = obj_data.split(',=> ');  

	                var new_id = +$('tbody tr:last').attr("id")+1; 

	                var new_name_num = +$('tbody tr:last').attr("name").match(/\d+/i)+1;

                  	if (button_click==1)$('tbody').append('<tr id='+new_id+' name=str_'+new_name_num+'><td>'+result[1]+'</td><td>'+result[2]+'</td><td>'+result[3]+'</td></tr>');

                  	if (button_click==2){ 

	                    var obj_data = ','+data.match(/=> .*/g).toString(); 

	                    var result = obj_data.split(',=> ');        

	                    if(inspect){	
	                    	//используются данные конструкции вместо replaceWith, что бы tr сохранялся, а не вставлялся на его место новый tr без id
	                    	$('#'+id_row+'>td').remove();			
	                    	$('#'+id_row).append('<td>'+result[1]+'</td><td>'+result[2]+'</td><td>'+result[3]+'</td>');
	                    	num_row = null;
	                    }

                	}

                  },
                  error:  function(xhr, str){
              alert('Возникла ошибка: ' + xhr.responseCode);
                  }
                });
              }

            });
        } 

        else if(button_click==3){ 

        		if(domsg){
					if(num_row!=null)inspect = confirm('Are you sure you want to delete the data in a row №'+num_row+'?');
					else{alert('Click on the line you want to delete')}
				}

				domsg = false;
				dovalid = false;

				if(inspect){

	                var msg   = $('#form').serialize(); 
	                $.ajax({
	                  type: 'POST',
	                  url: 'upload.php',
	                  data: msg,
	                  success: function(data) {    

						$('#'+id_row).remove();
						num_row = null;
	                  },
	                  error:  function(xhr, str){
	              alert('Возникла ошибка: ' + xhr.responseCode);
	                  }
	                });
           	   }

           	              
         }
} 

</script>


