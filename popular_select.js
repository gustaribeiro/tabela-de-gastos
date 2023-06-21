    $(function() {
    
      let category = $("#category")
      let subCategory = $("#subCategory")

      
      $("#category").on("change", function(){
        subCategory.append($('<option>', {value:0, text:"loading..."}))
        $.ajax({
          type: "POST",
          url: 'ajax_request.php',
          data:{categoryId:category.val()},
          success:response=>{
            let categories = JSON.parse(response) 

            addOption(subCategory, categories);
        
            }
         });
    });
  });

  function addOption(optionId, data){
    optionId.empty()
    data.forEach(function(category){
        optionId.append($('<option>', {value:category.sub_categoryId, text:category.name}))
    });
    console.log(data)
  }
