jQuery(function() {
	jQuery("#post").validate({
    rules: {
           dod_deal_price:{
       			number: true,
              	required: function (element) {
                     if(jQuery("#dod_is_in_dod").is(':checked')){
                        return true;
                     }else{
                        return false;
                     }
              	}
           },
           dod_start_date:{
              	required: function (element) {
                     if(jQuery("#dod_is_in_dod").is(':checked')){
                        return true;
                     }else{
                        return false;
                     }
              	}
           },
           dod_end_date:{
           		date:true,
              	required: function (element) {
                     if(jQuery("#dod_is_in_dod").is(':checked')){
                        return true;
                     }else{
                        return false;
                     }
              	}
           }
       },
        messages: {
	        dod_start_date: "Deal start date is required",
	        dod_end_date: "Deal end date is required",
	        dod_deal_price: {
	            required: "Deal price is required",
	            number: "Enter correct price",
	        }
	    }

  });
});