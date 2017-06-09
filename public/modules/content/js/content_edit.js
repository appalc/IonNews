	function readURL(input) {
	  if (input.files && input.files[0]) {
         var reader = new FileReader();
         reader.onload = function (e) {
         $('#blah').attr('src', e.target.result);
	    }
	  reader.readAsDataURL(input.files[0]);
	  }
	}
	function previewFile(){
    
       var preview = document.querySelector('img'); //selects the query named img
       var file    = document.querySelector('input[type=file]').files[0]; //sames as here
       var reader  = new FileReader();

       reader.onloadend = function () {
           preview.src = reader.result;
       }

       if (file) {
           reader.readAsDataURL(file); //reads the data as a URL
       } else {
           preview.src = "";
       }
  }

       function formValidator() {
         if(checkedArray.length==0){
          alert("Select atleast one user.");
          return false;
         }
         $("#userListing").append("<input type='hidden' name='checkedDetails[]' value='"+JSON.stringify(checkedArray)+"'/>");
         return true;
    }

   function changed(event){
  if(event.checked){
    checkedArray.push(event.value);
    console.log(checkedArray);

  }else{
    var a = checkedArray.indexOf(event.value);
    // console.log(a);
    checkedArray.splice(a, 1);
  }
  console.log(checkedArray);
}


      function changeorder()
      { 
       var roles_data=$( this ).val();
       var check=roles_data.indexOf("-1");            
        $("#user_info").empty();
        var table = "";
         if(check==-1){
            $.each(roles_data, function (key, values) {          
            keys=all_users_roles[values]['type'].toLowerCase();           
            if (typeof(checked_result[keys]) != 'undefined') {
            var checked_data =checked_result[keys];               
            $.each(checked_data ,function(key,new_users){  
            table+='<tr id="'+new_users.id+'"><td> <input class="checkbox" type="checkbox" name="check[]" value="'+new_users.id+'" checked></td>'+
                              '<td>'+new_users.name+'</td>'+
                  '<td>'+new_users.company+'</td><td>'+new_users.role+'</td></tr>';   
            });
            }  

          if (typeof(uncheck_result[keys]) != 'undefined') {                 
            var unchecked_data =uncheck_result[keys]; 
            $.each(unchecked_data ,function(key,new_users){  
            table+='<tr id="'+new_users.id+'"><td> <input class="checkbox" type="checkbox" onchange="changed(this);" name="check[]" value="'+new_users.id+'"></td>'+
                              '<td>'+new_users.name+'</td>'+
                  '<td>'+new_users.company+'</td><td>'+new_users.role+'</td></tr>';     
            });
            }           
            });
         }
          else{
            $.each(checked_result, function (key, values) {
            $.each(values, function(key,value){                                     
            table+='<tr id="'+value.id+'"><td> <input class="checkbox" type="checkbox" name="check[]" value="'+value.id+'" checked></td>'+
                              '<td>'+value.name+'</td>'+
                  '<td>'+value.company+'</td><td>'+value.role+'</td></tr>';
            });
            });
            $.each(uncheck_result, function (key, values) {
            $.each(values, function(key,value){
            table+='<tr id="'+value.id+'"><td> <input class="checkbox" type="checkbox" name="check[]" value="'+value.id+'"></td>'+
                              '<td>'+value.name+'</td>'+
                  '<td>'+value.company+'</td><td>'+value.role+'</td></tr>';
            });
            });
            }                      
            $("#user_info").html(table);          
         }
        $( "select.user_group" ).change( changeorder );
         changeorder();




         