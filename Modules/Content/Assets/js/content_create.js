function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}


 function formValidator() {
     
     if(category_check.length==0)
     {
      alert("Please Assign at least one category");
      return false;
     }
     return true;
}
function dataTableAssign(){
	dt=$("#User_data").DataTable({
	    "initComplete": function( settings, json ) {
	        $('.dataTables_filter').find('input[typcd e=search]').attr('type','text');
	    },
	    "bPaginate": true,
	    "bautoWidth": true,
	     // "destroy" : true,
	    "pagingType": "full_numbers",
	    "pageLength": 10,
	    "lengthMenu": [10, 25, 50, 100],
	    "dom": 'T<"clear">lfrtip',
	    "order": [[ 1, "desc" ]],
	    "initComplete": function( settings, json ) {
	        $('.dataTables_filter').find('input[type=search]').attr('type','text');
	    },
	    tableTools: {
	        "sSwfPath":"http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf",
	        aButtons: ['csv']
	    }
	});
}


  
function changed(event){
	if(event.checked){
		checkedArray.push(event.value);
	}else{
		var a = checkedArray.indexOf(event.value);
		console.log(a);
		checkedArray.splice(a, 1);
	}
	console.log(checkedArray);
}

function crawl() {
 	var urls=$(".form-control").val();

 	if(!urls)
 	{
  		alert ("You have not mention Url Address");
   		return false;
 	}
    $.ajax({
    type: 'GET',
    data: {url: urls},
    url: crawlUrl,
    success: function(result) {
		console.log(result);
         $(".user-types").show();
         if(result.img_count==0)
         $(".img-info").hide();
         else 
           $(".img-info").show();
         $(".custom_img").show();
        results = result;
        $('#sub_title').val(result.sub_title);
        $('#title').val(result.title);

        $("#syndata").empty();
        var table = "";
        var i = 1;
        var counter = 0;
        if (result.status == 200)
        {   
            $.each(result, function (key, value) {


                if (counter == result.count|| i==5)
                    return false;
                if (value.desc != null) {
                    counter++;
                    $('#content').append(value.desc + '   <br>');
                  }
                if(result.img_count>counter)
                table+='<tr><td style="text-align: center;">'+
                        '<input  type="radio" name="image" onchange="restImageView()" value ="'+value.img_url+'"/></td>'+
                        '<td><img id="blah" onclick="fullViews(this)" name="" src="'+value.img_url+'" alt="'+value.img_name+'" width="100"  class="model_rolling" data-toggle="modal" data-target="#myModal" style="margin-top:0px;margin-bottom:0px;text-align:center;">'+
                        '<input type="hidden" name="img'+i+'" value="'+value.img_url+'" style="opacity: 0;"/></td></tr>';
                i++;

            });
            $("#syndata").html(table);
    }else {
          alert("No img are found in this given URL");
        }
    },
    error: function(xhr, desc, err) {
        console.log(xhr);
    }
});

              

 // $.ajax({
 //    type: 'GET',
 //    url: userUrl,
 //    success: function(result) {
 //        $("#user_info").empty();
 //        var table = "";
 //        var i = 1;
 //        all_users_info=result;
 //       $.each(result, function (key, values) {
 //        $.each(values, function(key,value){                    

 //        table+='<tr id="'+value.id+'"><td> <input class="checkbox" type="checkbox" onchange="changed(this);" name="check[]" value="'+value.id+'"></td>'+
 //                  '<td>'+value.name+'</td>'+
 //      '<td>'+value.company+'</td><td>'+value.role+'</td></tr>';
 //                i++;

 //            });
 //          });
            
 //            $("#user_info").html(table);
             
 //            dataTableAssign();



  
 //    },
 //    error: function(xhr, desc, err) {
 //        console.log(xhr);}

    
 //  });

}
      
      function changeorder()
      { 
        roles_data=$( this ).val();
        console.log(roles_data);
         var check=roles_data.indexOf("-1");     
        dt.destroy();        
        $("#user_info").empty();
        var table = "";
          
         if(check==-1)
         {
        $.each(roles_data, function (key, values) {          
          keys=all_users_roles[values]['type'].toLowerCase();
          console.log(keys);
           
          if (typeof(all_users_info[keys]) != 'undefined') { 
          var data =all_users_info[keys]; 
          console.log(data);      
          $.each(data ,function(key,new_users){  
          table+='<tr id="'+new_users.id+'"><td> <input class="checkbox" type="checkbox" onchange="changed(this);" name="check[]" value="'+new_users.id+'"></td>'+
                              '<td>'+new_users.name+'</td>'+
                  '<td>'+new_users.company+'</td><td>'+new_users.role+'</td></tr>';     
            });
            }
            });
         }
          else {
             $.each(all_users_info, function (key, values) {     
                
             $.each(values ,function(key,new_users){ 
                

             table+='<tr id="'+new_users.id+'"><td> <input class="checkbox" type="checkbox" onchange="changed(this);" name="check[]" value="'+new_users.id+'"></td>'+
                              '<td>'+new_users.name+'</td>'+
                  '<td>'+new_users.company+'</td><td>'+new_users.role+'</td></tr>';     
            });
            });

          }

                        
            $("#user_info").html(table);             
            dataTableAssign();

         
      }
     



	function Custom() {
		$(".user-types").show();
		$(".custom_img").show();
		$.ajax({
				type: 'GET',
				url: userUrl,
				success: function(result) {
				$("#user_info").empty();
				var table = "";
				var i = 1;
				all_users_info=result;
				$.each(result, function (key, values) {
				$.each(values, function(key,value){                   
 						table+='<tr id="'+value.id+'"><td> <input class="checkbox" type="checkbox" onchange="changed(this);" name="check[]" value="'+value.id+'"></td>'+
						'<td>'+value.name+'</td>'+
						'<td>'+value.company+'</td><td>'+value.role+'</td></tr>';
						i++;
				});
				});

	    $("#user_info").html(table);
	    dataTableAssign();
	},
	error: function(xhr, desc, err) {
	console.log(xhr);}
	});         
	};