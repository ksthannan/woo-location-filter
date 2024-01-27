(function($){
    $(document).ready(function(){

        $('#product_location').select2({
            placeholder: locationfilter.choose_an_opt_str,
        });
        
        $('body').on('click', '.dokan-add-new-product', function(){
            $('#product_location').select2({
                placeholder: locationfilter.choose_an_opt_str,
            });
        });
        

        if(locationfilter.add_location_permission == '1'){

            $('<div id="wpl_tax_location" class="wpl_tax_location"><span class="wpl_btn button" id="wpl_new_location">'+locationfilter.location_btn_str+'</span></div>').insertBefore('.wcfm_product_taxonomy_location #location');


            $('body').on('click', '#wpl_new_location', function(){
                $('#wpl_tax_location').html('<div class="wpl-form-group dokan-form-group"><input class="wcfm-text dokan-form-control" type="text" name="wpl_tax_location_field" id="wpl_tax_location"></div>');
                $('#wpl_tax_location').append('<span class="wpl_btn button" id="wpl_new_location_close">'+locationfilter.choose_btn_str+'</span>');
                $('#location').fadeOut();
                $('#product_location').fadeOut();
            });
            $('body').on('click', '#wpl_new_location_close', function(){
                $('#wpl_tax_location').html('<span class="wpl_btn button" id="wpl_new_location">'+locationfilter.location_btn_str+'</span>');
                $('#location').fadeIn();
                $('#product_location').fadeIn();
            });
    
            $('body').on('click', '.product_taxonomy_checklist_location input[type=checkbox]', function(){
                if ($(this).is(':checked')) {
                    $('.product_taxonomy_checklist_location input[type=checkbox]').not(this).prop('checked', false);
                  }
            });
        }



        



    });
})(jQuery);


