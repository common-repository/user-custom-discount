jQuery(document).ready(function($){
	
    	$(document).on('change', 'select.cust_list', function (e) {
    		var cid=$(this).val();
    		jQuery.ajax({
    			    type: "POST",
    			    url: ajaxurl,
    			    context: this,
    			    data: { action: 'beeplug_ucdp_get_discount_list_action' , cust_id: cid }
    			  	}).done(function( msg ) {
                   var resp_content="";
                   var res = JSON.parse(msg);
                   if(res.counter > 0)
                   {
                      
                      for(var i=0;i<res.counter;i++)
                      {
                          
                          resp_content +='<tr><td><select name="item_cat[]" class="form-control item_cat">';
                          //category Loop
                          //console.log(res.bee_cat[i].length);
                          res.bee_cat[i].forEach(function (item, index) {
                            var cat_id=item['cat_id'];
                            var cstr=item['cat_sel'];
                            var cat_name=item['cat_name'];
                            resp_content +='<option '+cstr+' value="'+cat_id+'">'+cat_name+'</option>';                            
                          })

                          resp_content +='</select></td><td><select name="item_scat[]" class="form-control item_scat"><option value="">Select Sub Category</option>';
                           //Subcategory Loop
                           if (res.bee_scat != null )
                           {
                              if(res.bee_scat[i].length>0)
                              {
                                  res.bee_scat[i].forEach(function (item, index) {
                                    var scat_id=item['scat_id'];
                                    var scstr=item['scat_sel'];
                                    var scat_name=item['scat_name'];
                                    resp_content +='<option '+scstr+' value="'+scat_id+'">'+scat_name+'</option>';                           
                                  })
                              }
                              else{
                                  resp_content +='';
                              }
                            }
                          resp_content +='</select></td><td><select name="item_prod[]" class="form-control item_prod"><option value="">Select Product</option>';
                          //Product Loop
                          res.bee_prod[i].forEach(function (item, index) {
                            var p_id=item['prod_id'];
                            var pstr=item['prod_sel'];
                            var p_name=item['prod_name'];
                            resp_content +='<option '+pstr+' value="'+p_id+'">'+p_name+'</option>';
                            
                          })
                          resp_content +='</select></td><td><input type="text" name="disc_per[]" class="form-control item_disc" value="'+res.discount_list
[i]+'"></td><td><button type="button" name="remove" class="btn btn-danger btn-sm remove">-</button></td></tr>';
       
                      }                      
                   }
    			         $('#item_table').append(resp_content);                   
     			});
    	});
    	$(document).on('change', 'select.item_cat', function (e) {
        	var cat_id=$(this).val();
        	var scathtml="";
          var html="";
          var prodhtml="";
          var j=0;
        	jQuery.ajax({
    			    type: "POST",
    			    url: ajaxurl,
    			    context: this,
    			    data: { action: 'beeplug_ucdp_subcategory_action' , cat_id: cat_id }
    			  	}).done(function( msg ) {                                 
                  console.log(msg);
                  var res = JSON.parse(msg);
                  console.log(res);                  
                    scathtml +='<option value="">Select Sub Category</option>';                  
                    prodhtml +='<option value="">Select Product</option>';
                    res.scat_ids.forEach(function (item, index) {

                      html +='<option value="'+item+'">'+res.scat_names[j]+'</option>';
                      j+=1;


                    })
                  if(res.scat_count>0){                    
    			          //$(this).parent('td').parent('tr').find('select.item_prod').html(prodArray[1]);
                    $(this).parent('td').parent('tr').find('select.item_scat').html(scathtml+html);
                  }
                  else{
                    $(this).parent('td').parent('tr').find('select.item_prod').html(prodhtml+html);
                    $(this).parent('td').parent('tr').find('select.item_scat').html(scathtml);
                  }
                  
     			});
        	
    	});
    	$(document).on('change', 'select.item_scat', function (e) {
    		var cat_id=$(this).val();
        	var html="";var j=0;
        	jQuery.ajax({
    			    type: "POST",
    			    url: ajaxurl,
    			    context: this,
    			    data: { action: 'beeplug_ucdp_product_list_action' , cat_id: cat_id }
    			  	}).done(function( msg ) {
                   var res = JSON.parse(msg);
                   res.prod_ids.forEach(function (item, index) {
                        html +='<option value="'+item+'">'+res.prod_names[j]+'</option>';
                        j+=1;
                    })
                   $(this).parent('td').parent('tr').find('select.item_prod').html(html);
                   
     			});
    	});
      $(document).on('click', '.add', function(){

              jQuery.ajax({
        			    type: "POST",
        			    url: ajaxurl,
        			    data: { action: 'beeplug_ucdp_category_action' , param: 'st1' }
      			  	}).done(function( msg ) {
                  console.log(msg);
                  var res = JSON.parse(msg);
                  console.log(res.cat_ids.length);
      			      var html = '';var j=0;
                  
      					  html += '<tr>';
      					  html += '<td><select name="item_cat[]" class="form-control item_cat"><option value="">Select Category</option>';
                  res.cat_ids.forEach(function (item, index) {
                    html += '<option value="'+item+'">'+res.cat_names[j]+'</option>';
                    j+=1;
                  })
                  html += '</select></td>';
      					  html += '<td><select name="item_scat[]" class="form-control item_scat"></select></td>';
      					  html += '<td><select name="item_prod[]" class="form-control item_prod"></select></td>';
      					  html += '<td><input type="text" name="disc_per[]" class="form-control item_disc" /></td>';
      					  html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove">-</button></td></tr>';
      					  $('#item_table').append(html);
                              
       			  }); 	
      
     	  });
     
     $(document).on('click', '.remove', function(){
      $(this).closest('tr').remove();
     });
     
     $('#insert_form').on('submit', function(event){
        event.preventDefault();
        var error = '';
        $('.cust_list').each(function(){
         var count = 1;
         if($(this).val() == '')
         {
          error += "<p>Select Customer at "+count+" Row</p>";
          return false;
         }
         count = count + 1;
        });
        $('.item_cat').each(function(){
         var count = 1;
         if($(this).val() == '')
         {
          error += "<p>Select Category at "+count+" Row</p>";
          return false;
         }
         count = count + 1;
        });
      
        // $('.item_scat').each(function(){
        //  var count = 1;
        //  if($(this).val() == '')
        //  {
        //   error += "<p>Select Subcategory at "+count+" Row</p>";
        //   return false;
        //  }
        //  count = count + 1;
        // });
        
        // $('.item_prod').each(function(){
        //  var count = 1;
        //  if($(this).val() == '')
        //  {
        //   error += "<p>Select Product at "+count+" Row</p>";
        //   return false;
        //  }
        //  count = count + 1;
        // }); 
        $('.item_disc').each(function(){
         var count = 1;
         if($(this).val() == '')
         {
          error += "<p>Enter Discount value at "+count+" Row</p>";
          return false;
         }
         count = count + 1;
        });
        var form_data = $(this).serialize();
        if(error == '')
        {
        	$('table#item_table:before').css("display","block");
        	$(".btn-info").attr('disabled', true);
        	jQuery.ajax({
      			    type: "POST",
      			    url: ajaxurl,
      			    context: this,
      			    data:form_data,
      			  	}).done(function( msg ) {

      			  		if(msg == 'ok')
      				     {
      				      $(".btn-info").removeAttr('disabled');
      				      $('table#item_table:before').css("display","none");
      				      $('#item_table').find("tr:gt(1)").remove();
      				      $('#error').html('<div class="alert alert-success">Discount Rules Added Successfully!</div>');
      				      setTimeout(function() {
      						    location.reload();
      						}, 2000);
      				     }
      				     else{
      				     	$(".btn-info").removeAttr('disabled');
      				     	$('table#item_table:before').css("display","none");
      				      	$('#error').html('<div class="alert alert-danger">Error! Problem Inserting Data. Try Again</div>');
      				      		      	

      				     }
      			         
       	      });
         
        }
        else
        {
         $('#error').html('<div class="alert alert-danger">'+error+'</div>');
        }
     });
});