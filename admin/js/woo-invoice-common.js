(function ( $ ) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     */
    $(
        function () {
            $("#doaction, #doaction2").click(
                function (event) {
                    var actionselected = $(this).attr("id").substr(2),
                        getAction = $("select[name='" + actionselected + "']").val();
                    if(getAction==="wpifw_bulk_invoice") {
                        event.preventDefault();
                        var wpifwOrderIds = [];
                        $("tbody th.check-column input[type='checkbox']:checked").each(
                            function () {
                                wpifwOrderIds.push($(this).val());
                            }
                        );

                        if (!wpifwOrderIds.length) {
                              alert("You have to select orders first!");
                              return false;
                        }

                        var order_ids = wpifwOrderIds.join(","),
                            URL;
                        if (wpifw_ajax_obj_2.wpifw_ajax_url_2.indexOf("?") !== -1) {
                            URL = wpifw_ajax_obj_2.wpifw_ajax_url_2+'&action=wpifw_generate_invoice&order_ids='+order_ids+'&_wpnonce='+wpifw_ajax_obj_2.nonce;
                        } else {
                            URL = wpifw_ajax_obj_2.wpifw_ajax_url_2+'?action=wpifw_generate_invoice&order_ids='+order_ids+'&_wpnonce='+wpifw_ajax_obj_2.nonce;
                        }

                        window.open(URL,'_blank');

                        return false;
                    } else if(getAction==="wpifw_bulk_invoice_packing_slip") {

                        event.preventDefault();
                        var wpifwOrderIds = [];
                        $('tbody th.check-column input[type="checkbox"]:checked').each(
                            function () {
                                wpifwOrderIds.push($(this).val());
                            }
                        );

                        if (!wpifwOrderIds.length) {
                            alert('You have to select orders first!');
                            return false;
                        }

                        var order_ids=wpifwOrderIds.join(',');
                        var URL;
                        if (wpifw_ajax_obj_2.wpifw_ajax_url_2.indexOf("?") !== -1) {
                            URL = wpifw_ajax_obj_2.wpifw_ajax_url_2+'&action=wpifw_generate_invoice_packing_slip&order_ids='+order_ids+'&_wpnonce='+wpifw_ajax_obj_2.nonce;
                        } else {
                            URL = wpifw_ajax_obj_2.wpifw_ajax_url_2+'?action=wpifw_generate_invoice_packing_slip&order_ids='+order_ids+'&_wpnonce='+wpifw_ajax_obj_2.nonce;
                        }

                        window.open(URL,'_blank');

                        return false;
                    } else if(getAction==="wpifw_generate_shipping_label") {
                        event.preventDefault();
                        var wpifwOrderIds = [];
                        $('tbody th.check-column input[type="checkbox"]:checked').each(
                            function () {
                                wpifwOrderIds.push($(this).val());
                            }
                        );

                        if (!wpifwOrderIds.length) {
                            alert('You have to select orders first!');
                            return false;
                        }

                        var order_ids=wpifwOrderIds.join(',');
                        var URL;
                        if (wpifw_ajax_obj_2.wpifw_ajax_url_2.indexOf("?") !== -1) {
                            var total_order = order_ids.split(",").length;
                            if( total_order < 3 ){
                                var  column = total_order;
                                URL = wpifw_ajax_obj_2.wpifw_ajax_url_2+'&action=wpifw_generate_shipping_label&paper_size=A4&column='+column+'&row='+column+'&order_ids='+order_ids+'&_wpnonce='+wpifw_ajax_obj_2.nonce;
                            }else{
                                URL = wpifw_ajax_obj_2.wpifw_ajax_url_2+'&action=wpifw_generate_shipping_label&paper_size=A4&column=3&row=3&order_ids='+order_ids+'&_wpnonce='+wpifw_ajax_obj_2.nonce;
                            }
                        } else {
                            var total_order = order_ids.split(",").length;
                            if( total_order < 3 ){
                                var  column = total_order;
                                URL = wpifw_ajax_obj_2.wpifw_ajax_url_2+'?action=wpifw_generate_shipping_label&paper_size=A4&column='+column+'&row='+column+'&order_ids='+order_ids+'&_wpnonce='+wpifw_ajax_obj_2.nonce;
                            }else{
                                URL = wpifw_ajax_obj_2.wpifw_ajax_url_2+'?action=wpifw_generate_shipping_label&paper_size=A4&column=3&row=3&order_ids='+order_ids+'&_wpnonce='+wpifw_ajax_obj_2.nonce;

                            }

                        }

                        window.open(URL,'_blank');

                        return false;
                    } else if(getAction==="wpifw_order_list") {

                        event.preventDefault();
                        var wpifwOrderIds = [];
                        $('tbody th.check-column input[type="checkbox"]:checked').each(
                            function () {
                                wpifwOrderIds.push($(this).val());
                            }
                        );

                        if (!wpifwOrderIds.length) {
                            alert('You have to select orders first!');
                            return false;
                        }

                        var order_ids=wpifwOrderIds.join(',');
                        var URL;
                        if (wpifw_ajax_obj_2.wpifw_ajax_url_2.indexOf("?") !== -1) {
                            URL = wpifw_ajax_obj_2.wpifw_ajax_url_2+'&action=wpifw_generate_order_list&order_ids='+order_ids+'&_wpnonce='+wpifw_ajax_obj_2.nonce;
                        } else {
                            URL = wpifw_ajax_obj_2.wpifw_ajax_url_2+'?action=wpifw_generate_order_list&order_ids='+order_ids+'&_wpnonce='+wpifw_ajax_obj_2.nonce;
                        }

                        window.open(URL,'_blank');

                        return false;

                    } else if(getAction==="wpifw_csv_order_list") {

                        event.preventDefault();
                        var wpifwOrderIds = [];
                        $('tbody th.check-column input[type="checkbox"]:checked').each(
                            function () {
                                wpifwOrderIds.push($(this).val());
                            }
                        );

                        if (!wpifwOrderIds.length) {
                            alert('You have to select orders first!');
                            return false;
                        }

                        var order_ids=wpifwOrderIds.join(',');
                        var URL;
                        if (wpifw_ajax_obj_2.wpifw_ajax_url_2.indexOf("?") !== -1) {
                            URL = wpifw_ajax_obj_2.wpifw_ajax_url_2+'&action=wpifw_generate_csv_order_list&order_ids='+order_ids+'&_wpnonce='+wpifw_ajax_obj_2.nonce;
                        } else {
                            URL = wpifw_ajax_obj_2.wpifw_ajax_url_2+'?action=wpifw_generate_csv_order_list&order_ids='+order_ids+'&_wpnonce='+wpifw_ajax_obj_2.nonce;
                        }

                        window.open(URL,'_blank');

                        return false;
                    }
                }
            );// end;

            // Show Hide add field section in bulk download tab.
            if( $("#wpifw_bulk_type" ).find(":selected").val() == 'WPIFW_CSV_DOWNLOAD'){
                $('._winvoice-add-csv-fields').show()
            }else{
                $('._winvoice-add-csv-fields').hide();
            }
            $("#wpifw_bulk_type" ).change(function (){
                if( this.value == 'WPIFW_CSV_DOWNLOAD'){
                    $('._winvoice-add-csv-fields').show()
                }else{
                    $('._winvoice-add-csv-fields').hide()
                }
            });
        }
    );

    /**
     * Added postbox toggle
     */
    $('._winvoice_docs .toggle-indicator').on('click', function (){
        $(this).closest('.postbox').toggleClass('closed');
    });
})(jQuery);
