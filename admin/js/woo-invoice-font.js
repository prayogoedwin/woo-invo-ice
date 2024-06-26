jQuery(function($) {
    'use strict';

    // Default hide loading image.
    $('#loading-image').hide();

    // Default hide warning icon
    $('#font-warning').hide();

    // Default hide success icon.
    $('#success').hide();

    // Default hide downloading button.
    $('#wpifw_pdf_invoice_font_downloading').hide();


    $('#wpifw_pdf_invoice_download_font').on('click', function(e) {
        // Stop scroll when click on download button
        e.preventDefault();

        var file_data = $('#woo-invoice-font-upload').prop('files')[0];

        // Check for blank upload request.
        if( !file_data ){
            alert('Invalid request');
            return false;
        }

        // Check zip extention for upload.
        var accept_file_type = file_data.name.split( '.' ).pop()
        var ext = ['zip', 'ttf', 'otf'];
        if( ! ext.includes(accept_file_type) ){
            alert('please upload file type .zip, .ttf, .otf');
            return false;
        }

        // Hide download button after click button.
        $('#wpifw_pdf_invoice_download_font').hide();

        // Hide success icon after click button.
        $('#success').hide();

        // Show warning icon
        $('#font-warning').hide();

        // Error Message
        $('#errors').hide();

        // Show downloading button.
        $('#wpifw_pdf_invoice_font_downloading').show();

        // Show loading image when click.
        $('#loading-image').show();

      //  console.log(file_data);
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('action', 'woo_invoice_font_download_ajax');
        form_data.append('_ajax_nonce', wpifw_ajax_obj_font.nonce);

        $.ajax({
            url: wpifw_ajax_obj_font.wpifw_ajax_font_url,
            type: 'POST',
            contentType: false,
            processData: false,
            data: form_data,
            dataType: 'json',
            success:function (data) {
                console.log(data.data.font_name);
                if(data.success == true){
                    // Reset form data.
                    $('#woo-invoice-font-upload').val('');

                    // Show success message when uplaoded file.
                    $('#success').html('<span class="dashicons dashicons-yes"></span>');

                    // Hide Loading Image
                    $('#loading-image').hide();

                    // Show success icon
                    $('#success').show();

                    // Show download button
                    $('#wpifw_pdf_invoice_download_font').show();

                    // Hide downloading button
                    $('#wpifw_pdf_invoice_font_downloading').hide();
                }else{
                    console.log(data);
                    // Reset input value.
                    $('#woo-invoice-font-upload').val('');

                    // Show error message.
                    $('#errors').show().html(error.data);

                    // Hide Loading Image
                    $('#loading-image').hide();

                    // Show download button
                    $('#wpifw_pdf_invoice_download_font').show();

                    // Hide downloading button
                    $('#wpifw_pdf_invoice_font_downloading').hide();

                    // Show warning icon
                    $('#font-warning').show();
                }

            },
            error:function (error) {

                console.log(error);

                // Reset input value.
                $('#woo-invoice-font-upload').val('');

                // Show error message.
                $('#errors').show().html(error.data);

                // Hide Loading Image
                $('#loading-image').hide();

                // Show download button
                $('#wpifw_pdf_invoice_download_font').show();

                // Hide downloading button
                $('#wpifw_pdf_invoice_font_downloading').hide();

                // Show warning icon
                $('#font-warning').show();
            }
        });
    });
});
