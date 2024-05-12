(function($) {
    'use strict';
    /**
     * Woo Invoice Dropbox Backup.
     * @type {{initialHelper: WooInvoice.initialHelper}}
     */
    var WooInvoice = function(){

        /**
         * Dropbox enable/disable toggle.
         */
        var dropboxToggle = function() {
            $("#wpifw_pdf_invoice_upload_to_dropbox").on('change', function () {
                if( $(this).is(":checked") == true) {
                   $('._winvoice_dropbox_client_section').show();
                }else{
                    $('._winvoice_dropbox_client_section').hide();
                }
            });
        };

        /**
         * Setup dropbox credentials.
         */
        var enableDropboxApi = function(){
            // Enable Dropbox API.
            $("#wpifw_pdf_invoice_upload_to_dropbox").on('change', function () {

                // Enable upload pdf to dropbox.
                if( $(this).is(":checked") == true) {
                    $("div#loading-image2 img").css('display', 'block');
                    $("span#success2 .dashicons.dashicons-yes").css('display', 'none');
                    $("div#warning2 span").css('display', 'none');
                    // Request for checking api file already exist or not.
                    $.ajax({
                        url: wpifw_ajax_obj_dropboxapi.wpifw_ajax_dropboxapi_url,
                        type:'post',
                        dataType: 'json',
                        data:{
                            action: 'woo_invoice_dropboxapi_callback',
                            _ajax_nonce: wpifw_ajax_obj_dropboxapi.nonce,
                        },
                        success:function(data){
                            // Dropbox API file already exist.
                            if(data.success == true) {
                                $("div#loading-image2 img").css('display', 'none');
                                $("span#success2 .dashicons.dashicons-yes").css('display', 'block');
                                $("div#warning2 span").css('display', 'none');
                                alert( 'DropBox API is already exist' );

                            }else{
                                // Request for downloading api file.
                                $.ajax({
                                    url: wpifw_ajax_obj_dropboxapi.wpifw_ajax_dropboxapi_url,
                                    type:'post',
                                    dataType: 'json',
                                    data:{
                                        action: 'woo_invoice_dropboxapi_download',
                                        _ajax_nonce: wpifw_ajax_obj_dropboxapi.nonce,
                                    },
                                    success:function(data){
                                        if(data.success == true) {
                                            $("div#loading-image2 img").css('display', 'none');
                                            $("span#success2 .dashicons.dashicons-yes").css('display', 'block');
                                            $("div#warning2 span").css('display', 'none');

                                        }else{
                                            $("div#loading-image2 img").css('display', 'none');
                                            $("span#success2 .dashicons.dashicons-yes").css('display', 'none');
                                            $("div#warning2 span").css('display', 'block');
                                        }
                                    },
                                    error:function(error){
                                        console.log(error)
                                    }
                                })
                            }
                        },
                        error:function(error){
                            console.log(error)
                        }
                    })
                }
            })

        }
        /**
         * Check folder Existence from dropbox.
         */
        var checkFolderExists = function(){
            $("#wpifw_invoice_dropboxapi_folder_path").on('input', function () {

                var fName =  $(this).val()
                // Request for checking api file already exist or not.
                $.ajax({
                    url: wpifw_ajax_obj_dropboxapi.wpifw_ajax_dropboxapi_url,
                    type:'post',
                    dataType: 'json',
                    data:{
                        action: 'woo_invoice_check_dropbox_folder_exist',
                        fName:fName,
                        _ajax_nonce: wpifw_ajax_obj_dropboxapi.nonce,
                    },
                    success:function(data){
                        // Dropbox API folder already exist.
                        if(data.success == true) {
                            displayFolderQueryResult("This path exist. Invoice will paste on this folder.")
                        }else{
                            displayFolderQueryResult("This path does not exist. This folder will be created.")

                        }
                    },
                    error:function(error){
                        console.log(error)
                    }
                })

            })
            // Callback function for displaying dropbox query result.
            function displayFolderQueryResult($sting ) {
                var result = $('#show_ajax_query_result');
                result.css('display', 'block');
                result.html($sting)
                setInterval(function () {
                    result.fadeOut(3000)
                }, 3000)
            }
        }

        /**
         * Sanitization for dropbox input.
         */
        var inputSanitization = function(){
            // Remove all whitespace and "/" from input value.
            $('#wpifw_invoice_settings_submit_btn').on('mouseover', function () {
                let dropboxFolder    = $('#wpifw_invoice_dropboxapi_folder_path').val();
                let trimedValue      = $.trim( dropboxFolder );
                trimedValue         = trimedValue.replace(/ /g, '');
                let firstChar        =  trimedValue.charAt(0);
                let lastChar         =  trimedValue.slice(-1);
                if( firstChar == '/' ) {
                    trimedValue = trimedValue.slice(1);
                }
                if( lastChar == '/' ) {
                    trimedValue = trimedValue.slice(0, -1)
                }
                $("#wpifw_invoice_dropboxapi_folder_path").val( trimedValue );
            })
        }

        /**
         * Initialization all function.
         */
        return {
            initialHelper:function(){
                dropboxToggle();
                enableDropboxApi();
                checkFolderExists();
                inputSanitization();
            }
        }
    }(jQuery);

    /* jQuery ready  */
    jQuery(document).on('ready',function() {WooInvoice.initialHelper();});
})(jQuery);
