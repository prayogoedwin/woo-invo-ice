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
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     */
    $(window).load(
        function () {
            $('.wpifw-show-data-collection-list').on(
                'click', function (e) {
                    e.preventDefault();
                    $('.tracker_collection_list').slideToggle('fast');
                }
            );
        }
    );
    // scroll add and remove class
    $(window).scroll(
        function () {
            if ($(this).scrollTop() + $(window).height() < $(document).height()-150) {
                $('._winvoice-save-changes-selector').addClass("_winvoice-save-changes");
            }
            else{
                $('._winvoice-save-changes-selector').removeClass("_winvoice-save-changes");
            }
        }
    );

    $(window).on(
        "mousewheel", function (e) {
            //if($(window).scrollTop() + $(window).height() > $(document).height()-150)  {

            //$("._winvoice-save-changes-selector").removeClass("_winvoice-save-changes");
            //} else {
            // $("._winvoice-save-changes-selector").addClass("_winvoice-save-changes");
            //}

            if($(window).scrollTop() + $(window).height() > $("._winvoice-seller-block").height()-100) {
                $("._winvoice-save-changes-selector2").removeClass("_winvoice-save-changes");

            } else {
                $("._winvoice-save-changes-selector2").addClass("_winvoice-save-changes");
            }

            if($(window).scrollTop() + $(window).height() > $("._winvoice-buyer-block").height()-200) {
                $("._winvoice-save-changes-selector3").removeClass("_winvoice-save-changes");

            } else {
                $("._winvoice-save-changes-selector3").addClass("_winvoice-save-changes");
            }

            if($(window).scrollTop() + $(window).height() > $("._winvoice-localization-invoice-block").height()+100) {
                $("._winvoice-save-changes-selector4").removeClass("_winvoice-save-changes");

            } else {
                $("._winvoice-save-changes-selector4").addClass("_winvoice-save-changes");
            }

            if($(window).scrollTop() + $(window).height() > $(document).height()-100) {

                $("._winvoice-save-changes-selector5").removeClass("_winvoice-save-changes");
            } else {
                $("._winvoice-save-changes-selector5").addClass("_winvoice-save-changes");
            }

            var initialContent = $("._winvoice-dashboard-content > li:eq(0)");
            $('._winvoice-dashboard-sidebar ._winvoice-sidebar-navbar-light').height(initialContent.parent().height()-23);
        }
    );



    $(
        function () {

            // Order List Invoice Button Redirect
            $(".wpifw_invoice_action_button").click(
                function (event) {
                    event.preventDefault();
                    var URL=$(this).attr('href');
                    window.open(URL,'_blank');
                    return false;
                }
            );


            //Bulk input date validation
            var from_date;
            var to_date;
            var toCheck   = 0;
            var fromCheck = 0;

            $('#Date-from').on(
                'change',function () {
                    from_date = Date.parse($(this).val());
                    fromCheck = 1;
                    if(toCheck && fromCheck) {
                        if(to_date<from_date) {
                            alert("Input date should be less than or equal Date To");
                            $('#Date-from').val("");
                            fromCheck = 0;
                        }
                    }

                }
            );

            $('#Date-to').on(
                'change',function () {
                    to_date = Date.parse($(this).val());
                    toCheck = 1;
                    if(toCheck && fromCheck) {
                        if(to_date<from_date) {
                            alert("Input date should be greater than or equal Date From");
                            $('#Date-to').val("");
                            toCheck = 0;

                        }
                    }

                }
            );


            var tabs = $('._winvoice-sidebar-navbar-nav > li > a'); //grab tabs
            var contents = $('._winvoice-dashboard-content > li'); //grab contents
            if(sessionStorage.getItem('activeSidebarTab') !== null ) {

                var activeSidebarTab = sessionStorage.getItem('activeSidebarTab');
                contents.hide(); //hide all contents
                tabs.removeClass('active'); //remove 'current' classes
                $(contents[activeSidebarTab]).show(); //show tab content that matches tab title index
                var activeTabSelector = $("._winvoice-sidebar-navbar-nav > li:eq( "+activeSidebarTab+" ) > a");
                activeTabSelector.addClass('active');
                /*$(this).addClass('active'); //add current class on clicked tab title*/
                $('._winvoice-dashboard-sidebar ._winvoice-sidebar-navbar-light').height($(contents[activeSidebarTab]).parent().height()-23);
            } else {
                var initialContent = $("._winvoice-dashboard-content > li:eq(0)");
                initialContent.css('display','block'); //show tab content that matches tab title index
                var activeTabSelector = $("._winvoice-sidebar-navbar-nav > li:eq(0) > a");
                activeTabSelector.addClass('active');
                $('._winvoice-dashboard-sidebar ._winvoice-sidebar-navbar-light').height(initialContent.parent().height()-23);
            }

            tabs.bind(
                'click',function (e) {
                    e.preventDefault();
                    var tabIndex = $(this).parent().prevAll().length;
                    contents.hide(); //hide all contents
                    tabs.removeClass('active'); //remove 'current' classes
                    $(contents[tabIndex]).show(); //show tab content that matches tab title index
                    $(this).addClass('active'); //add current class on clicked tab title

                    var selectedSidebarTab = $(this).parent().prevAll().length;
                    sessionStorage.setItem('activeSidebarTab', selectedSidebarTab);
                    $('._winvoice-dashboard-sidebar ._winvoice-sidebar-navbar-light').height(contents.parent().height()-25);
                }
            );


            function imageIsLoaded(e)
            {
                $('#_winvoice-preview-signature').attr('src', e.target.result);
            };

            //Datepicker
            flatpickr(
                "._winvoice-datepicker", {
                    "dateFormat":"n/j/Y",
                    "allowInput":true,
                    "onOpen": function (selectedDates, dateStr, instance) {
                        instance.setDate(instance.input.value, false);
                    }
                }
            );


            $(document).on(
                'change', '#wpifw_upload_signature', function (e) {
                    if (this.files && this.files[0]) {
                        var reader = new FileReader();
                        reader.onload = imageIsLoaded;
                        reader.readAsDataURL(this.files[0]);
                    }
                }
            );


            if($('#wpifw_enable_signature').is(':checked') ) {

                $('._winvoice-signature-btn').removeClass('_winvoice-disable');
                $('._winvoice-signature-preview').removeClass('_winvoice-disable');
                // $('._winvoice-signature-preview').css('display','block');

            } else {

                $('._winvoice-signature-btn').addClass('_winvoice-disable');
                $('._winvoice-signature-preview').addClass('_winvoice-disable');
                // $('._winvoice-signature-preview').css('display','none');

            }

            if($('#wpifw_enable_invoice_background').is(':checked') ) {

                $('._winvoice-invoice-background-btn').removeClass('_winvoice-disable');
                $('.wpifw_invoice_background_opacity').removeClass('_winvoice-disable');
                $('._winvoice-invoice-background-preview').removeClass('_winvoice-disable');
                // $('._winvoice-invoice-background-preview').css('display','block');

            } else {

                $('._winvoice-invoice-background-btn').addClass('_winvoice-disable');
                $('.wpifw_invoice_background_opacity').addClass('_winvoice-disable');
                $('._winvoice-invoice-background-preview').addClass('_winvoice-disable');
                // $('._winvoice-invoice-background-preview').css('display','none');

            }

            $(document).on(
                'click', '#wpifw_enable_invoice_background', function () {
                    if($(this).is(':checked') ) {

                        $('._winvoice-invoice-background-btn').removeClass('_winvoice-disable');
                        $('.wpifw_invoice_background_opacity').removeClass('_winvoice-disable');
                        $('._winvoice-invoice-background-preview').removeClass('_winvoice-disable');
                        // $('._winvoice-invoice-background-preview').css('display','block');

                        $.ajax(
                            {
                                url: wpifw_ajax_obj.wpifw_ajax_url,
                                type: 'post',
                                data: {
                                    _ajax_nonce: wpifw_ajax_obj.nonce,
                                    action: "wpifw_invoice_background_enabled"
                                },
                                success: function (response) {
                                }
                            }
                        );

                    } else {

                        $('._winvoice-invoice-background-btn').addClass('_winvoice-disable');
                        $('.wpifw_invoice_background_opacity').addClass('_winvoice-disable');
                        $('._winvoice-invoice-background-preview').addClass('_winvoice-disable');
                        //$('._winvoice-invoice-background-preview').css('display','none');

                        $.ajax(
                            {
                                url: wpifw_ajax_obj.wpifw_ajax_url,
                                type: 'post',
                                data: {
                                    _ajax_nonce: wpifw_ajax_obj.nonce,
                                    action: "wpifw_invoice_background_disabled"
                                },
                                success: function (response) {
                                }
                            }
                        );

                    }
                }
            );
            //////////////////////////////////////////////////////////////
            // get invoice product attribute.
            //////////////////////////////////////////////////////////////
            $('.wpifw_attr').selectize(
                {
                    plugins: ['drag_drop','remove_button'],
                    render: {
                        item: function (data, escape) {
                            return '<div class="item wpifw_selector">'+ escape(data.text) + '</div>';
                        }
                    },
                    onInitialize: function () {
                        var selectize = this;
                        $.ajax(
                            {
                                url: wpifw_ajax_obj.wpifw_ajax_url,
                                type: 'post',
                                data: {
                                    _ajax_nonce: wpifw_ajax_obj.nonce,
                                    action: "wpifw_get_product_attribute_show",
                                },
                                success: function (response) {
                                    //var selected_items = response.data;
                                    var selected_items = [].slice.call(response.data);
                                    var sorted_selected_items = selected_items.sort(
                                        function (a, b) {
                                            if (a < b) { return -1;
                                            } else if (a > b) { return 1;
                                            }
                                            return 0;
                                        }
                                    );
                                    selectize.setValue(sorted_selected_items);
                                }
                            }
                        );
                    }
                }
            );

            //////////////////////////////////////////////////////////////
            // Save Product attribute.
            //////////////////////////////////////////////////////////////
            $(document).on(
                'change', '.wpifw_attr', function (e) {
                    e.preventDefault();
                    $.ajax(
                        {
                            url: wpifw_ajax_obj.wpifw_ajax_url,
                            type: 'post',
                            data: {
                                _ajax_nonce: wpifw_ajax_obj.nonce,
                                action: "wpifw_product_attribute_show",
                                attribute: $(".wpifw_attr").val()
                            },
                            success: function (response) {
                            }
                        }
                    );
                }
            );



            //////////////////////////////////////////////////////////////
            // Show Product Column for invoice
            //////////////////////////////////////////////////////////////
            $('.wpifw_header').selectize(
                {
                    plugins: ['drag_drop','remove_button'],
                    maxItems: 10,
                    render: {
                        item: function (data, escape) {
                            return '<div class="item wpifw_selector">'+ escape(data.text) + '</div>';
                        }
                    },
                    onInitialize: function () {
                        var selectize = this;
                        $.ajax(
                            {
                                url: wpifw_ajax_obj.wpifw_ajax_url,
                                type: 'post',
                                data: {
                                    _ajax_nonce: wpifw_ajax_obj.nonce,
                                    action: "wpifw_get_product_column_show",
                                },
                                success: function (response) {
                                    //var selected_items = response.data;
                                    var selected_items = [].slice.call(response.data);
                                    // var sorted_selected_items = selected_items.sort(
                                    //     function (a, b) {
                                    //         if (a < b) { return -1;
                                    //         } else if (a > b) { return 1;
                                    //         }
                                    //         return 0;
                                    //     }
                                    // );
                                    selectize.setValue(selected_items);
                                }
                            }
                        );
                    }
                }
            );

            //////////////////////////////////////////////////////////////
            // Save Product Column.
            //////////////////////////////////////////////////////////////
            $(document).on(
                'change', '.wpifw_header', function (e) {
                    e.preventDefault();
                    $.ajax(
                        {
                            url: wpifw_ajax_obj.wpifw_ajax_url,
                            type: 'post',
                            data: {
                                _ajax_nonce: wpifw_ajax_obj.nonce,
                                action: "wpifw_select_product_column",
                                columns: $(".wpifw_header").val()
                            },
                            success: function (response) {
                            }
                        }
                    );
                }
            );


            //////////////////////////////////////////////////////////////
            // Show Product Column for packing slip
            //////////////////////////////////////////////////////////////
            $('.wpifw_packingslip_header').selectize(
                {
                    plugins: ['drag_drop','remove_button'],
                    maxItems: 4,
                    render: {
                        item: function (data, escape) {
                            return '<div class="item wpifw_selector">'+ escape(data.text) + '</div>';
                        }
                    },
                    onInitialize: function () {
                        var selectize = this;
                        $.ajax(
                            {
                                url: wpifw_ajax_obj.wpifw_ajax_url,
                                type: 'post',
                                data: {
                                    _ajax_nonce: wpifw_ajax_obj.nonce,
                                    action: "get_wpifw_packingslip_product_table_header",
                                },
                                success: function (response) {
                                    //var selected_items = response.data;
                                    var selected_items = [].slice.call(response.data);
                                    // var sorted_selected_items = selected_items.sort(
                                    //     function (a, b) {
                                    //         if (a < b) { return -1;
                                    //         } else if (a > b) { return 1;
                                    //         }
                                    //         return 0;
                                    //     }
                                    // );
                                    selectize.setValue(selected_items);
                                }
                            }
                        );
                    }
                }
            );

            //////////////////////////////////////////////////////////////
            // Save Product Column For Packing Slip.
            //////////////////////////////////////////////////////////////
            $(document).on(
                'change', '.wpifw_packingslip_header', function (e) {
                    e.preventDefault();
                    $.ajax(
                        {
                            url: wpifw_ajax_obj.wpifw_ajax_url,
                            type: 'post',
                            data: {
                                _ajax_nonce: wpifw_ajax_obj.nonce,
                                action: "wpifw_packingslip_product_table_header",
                                columns: $(".wpifw_packingslip_header").val()
                            },
                            success: function (response) {
                            }
                        }
                    );
                }
            );

            //////////////////////////////////////////////////////////////
            // Save CSV Bulk download fields.
            //////////////////////////////////////////////////////////////
            $(document).on(
                'change', '.wpifw_csv', function (e) {
                    e.preventDefault();
                    $.ajax(
                        {
                            url: wpifw_ajax_obj.wpifw_ajax_url,
                            type: 'post',
                            data: {
                                _ajax_nonce: wpifw_ajax_obj.nonce,
                                action: "wpifw_save_csv_fields",
                                attribute: $(".wpifw_csv").val()
                            },
                            success: function (response) {
                            }
                        }
                    );
                }
            );


            //////////////////////////////////////////////////////////////
            // display bulk download csv order fields.
            //////////////////////////////////////////////////////////////
            $('.wpifw_csv').selectize(
                {
                    plugins: ['drag_drop','remove_button'],
                    render: {
                        item: function (data, escape) {
                            return '<div class="item wpifw_selector">'+ escape(data.text) + '</div>';
                        }
                    },
                    onInitialize: function () {
                        var selectize = this;
                        $.ajax(
                            {
                                url: wpifw_ajax_obj.wpifw_ajax_url,
                                type: 'post',
                                data: {
                                    _ajax_nonce: wpifw_ajax_obj.nonce,
                                    action: "wpifw_get_csv_fields_show",
                                },
                                success: function (response) {
                                    //var selected_items = response.data;
                                    var selected_items = [].slice.call(response.data);
                                    var sorted_selected_items = selected_items.sort(
                                        function (a, b) {
                                            if (a < b) { return -1;
                                            } else if (a > b) { return 1;
                                            }
                                            return 0;
                                        }
                                    );
                                    selectize.setValue(sorted_selected_items);
                                }
                            }
                        );
                    }
                }
            );


            //////////////////////////////////////////////////////////////
            // Display Product Meta.
            //////////////////////////////////////////////////////////////
            $(document).on(
                'change', '._winvoice-product-post-meta', function (e) {
                    e.preventDefault();
                    var value=$(this).val();
                    $(this).attr("name",value+"_winvoice_post_meta_name");
                    $(this).prev("._winvoice-product-post-meta-label").attr("name",value+"_winvoice_post_meta_label");
                }
            );

            var metaHTML =$("._winvoice_meta_html").first().clone();

            $(document).on(
                'click', '._winvoice-add-meta', function (e) {
                    e.preventDefault();
                    $(this).siblings("._winvoice_meta").append(metaHTML.clone());
                    $("._winvoice_meta").children("._winvoice_meta_html").last().find("input").removeAttr('name');
                    $("._winvoice_meta").children("._winvoice_meta_html").last().find("select").removeAttr('name');
                    $("._winvoice_meta").children("._winvoice_meta_html").last().find("input").val('');
                    $("._winvoice_meta").children("._winvoice_meta_html").last().find("select").val('');
                    //$("._winvoice_meta").children("._winvoice_meta_html").last().append('<a href="#" class="_winvoice-delete-meta"><span class="dashicons dashicons-trash" style="font-size:37px;color:#D94D40"></span></a>');
                    //$('._winvoice-add-meta .dashicons-plus-alt').css("margin-left","-20px");
                }
            );

            $(document).on(
                'click', '._winvoice-delete-meta', function (e) {
                    e.preventDefault();
                    $(this).parent().remove();
                    if($("._winvoice_meta_html").length==="1") {
                        //$('._winvoice-add-meta .dashicons-plus-alt').css("margin-left","0px");
                    }
                }
            );
            //////////////////////////////////////////////////////////////
            //  End :  display Product Meta.
            //////////////////////////////////////////////////////////////

            //////////////////////////////////////////////////////////////
            // Display order meta for invoice.
            //////////////////////////////////////////////////////////////
            var metaOrderHTML = $("._winvoice_order_meta_html").first().clone();
            // clone a new input field for setting order mate.
            $(document).on(
                'click', '._winvoice-add-order-meta', function (e) {
                    e.preventDefault();
                    $(this).siblings("._winvoice_order_meta").append(metaOrderHTML.clone());
                }
            );
            // delete order meta.
            $(document).on(
                'click', '._winvoice-delete-order-meta', function (e) {
                    e.preventDefault();
                    $(this).parent().remove();
                    if($("._winvoice_order_meta_html").length==="1") {
                        //$('._winvoice-add-order-meta .dashicons-plus-alt').css("margin-left","0px");
                    }
                }
            );
            //////////////////////////////////////////////////////////////
            //  End :  display order meta  for invoice.
            //////////////////////////////////////////////////////////////


            //////////////////////////////////////////////////////////////
            // Display order meta for packing_slip.
            //////////////////////////////////////////////////////////////

            var metaOrderHTMLPs = $("._winvoice_order_meta_html_ps").first().clone();
            // clone a new input field for setting order mate.
            $(document).on(
                'click', '._winvoice-add-order-meta-ps', function (e) {
                    e.preventDefault();
                    $(this).siblings("._winvoice_order_meta_ps").append(metaOrderHTMLPs.clone());
                    //$("._winvoice_order_meta").children("._winvoice_order_meta_html").last().find("input").removeAttr('name');
                    //$("._winvoice_order_meta").children("._winvoice_order_meta_html").last().find("select").removeAttr('name');
                    //$("._winvoice_order_meta").children("._winvoice_order_meta_html").last().find("input").val('');
                    //$("._winvoice_order_meta").children("._winvoice_order_meta_html").last().find("select").val('');
                }
            );
            // delete order meta.
            $(document).on(
                'click', '._winvoice-delete-order-meta-ps', function (e) {
                    e.preventDefault();
                    $(this).parent().remove();
                    if($("._winvoice_order_meta_html_ps").length==="1") {
                        //$('._winvoice-add-order-meta .dashicons-plus-alt').css("margin-left","0px");
                    }
                }
            );
            //////////////////////////////////////////////////////////////
            //  End :  display order meta  for packing_slip.
            //////////////////////////////////////////////////////////////

            //////////////////////////////////////////////////////////////
            // Display Order Item Meta for invoice.
            //////////////////////////////////////////////////////////////

            // Get order item meta from database.

            // $.ajax({
            //     url: wpifw_ajax_obj.wpifw_ajax_url,
            //     type: 'post',
            //     data: {
            //         _ajax_nonce: wpifw_ajax_obj.nonce,
            //         action: 'woo_invoice_item_meta_query',
            //     },
            //     success: function (response){
            //         // console.log(response.data)
            //         let data = response.data
            //         let output = []
            //         $.each(data , function(key, value)
            //         {
            //             console.log(key, value)
            //             output.push(`<option value="${value.meta_key}">${value.meta_key}</option>`);
            //             $('#wpifw_order_item_meta_show').append($('<option>', {
            //                 value: value.meta_key,
            //                 text : value.meta_key
            //             }));
            //         });
            //         console.log($('#wpifw_order_item_meta_show').html())
            //        $('#wpifw_order_item_meta_show').html(output.join(''));
            //     },
            //     error: function (error){
            //         console.log(error)
            //     }
            // })

           // woo_invoice_item_meta_query

            $(document).on(
                'change', '._winvoice-order-item-meta', function (e) {
                    e.preventDefault();
                    var value=$(this).val();
                    $(this).attr("name",value+"_winvoice_order_item_meta_name");
                    $(this).prev("._winvoice-order-item-meta-label").attr("name",value+"_winvoice_order_item_meta_label");
                }
            );
            var metaOrderItemHTML =$("._winvoice_order_item_meta_html").first().clone();

            $(document).on(
                'click', '._winvoice-add-order-item-meta', function (e) {
                    e.preventDefault();
                    $(this).siblings("._winvoice_order_item_meta").append(metaOrderItemHTML.clone());
                    $("._winvoice_order_item_meta").children("._winvoice_order_item_meta_html").last().find("input").removeAttr('name');
                    $("._winvoice_order_item_meta").children("._winvoice_order_item_meta_html").last().find("select").removeAttr('name');
                    $("._winvoice_order_item_meta").children("._winvoice_order_item_meta_html").last().find("input").val('');
                    $("._winvoice_order_item_meta").children("._winvoice_order_item_meta_html").last().find("select").val('');
                    //$("._winvoice_order_item_meta").children("._winvoice_order_item_meta_html").last().append('<a href="#" class="_winvoice-delete-order-item-meta"><span class="dashicons dashicons-trash" style="font-size:37px;color:#D94D40"></span></a>');
                    //$('._winvoice-add-order-item-meta .dashicons-plus-alt').css("margin-left","-20px");
                }
            );

            $(document).on(
                'click', '._winvoice-delete-order-item-meta', function (e) {
                    e.preventDefault();
                    $(this).parent().remove();
                    if($("._winvoice_order_item_meta_html").length==="1") {
                        //$('._winvoice-add-order-item-meta .dashicons-plus-alt').css("margin-left","0px");
                    }
                }
            );
            //////////////////////////////////////////////////////////////
            //  End :  display Order Item Meta for invoice.
            //////////////////////////////////////////////////////////////


            //////////////////////////////////////////////////////////////
            // Display Order Item Meta for packing slip.
            //////////////////////////////////////////////////////////////
            $(document).on(
                'change', '._winvoice-order-item-meta-ps', function (e) {
                    e.preventDefault();
                    var value=$(this).val();
                    $(this).attr("name",value+"_winvoice_order_item_meta_name_ps");
                    $(this).prev("._winvoice-order-item-meta-label-ps").attr("name",value+"_winvoice_order_item_meta_label_ps");
                }
            );
            var order = $("._winvoice_order_item_meta_html_ps").first().clone();
            $(document).on(
                'click', '._winvoice-add-order-item-meta-ps', function (e) {
                    e.preventDefault();
                    $(this).siblings("._winvoice_order_item_meta_ps").append(order.clone());
                    $("._winvoice_order_item_meta_ps").children("._winvoice_order_item_meta_html_ps").last().find("input").removeAttr('name');
                    $("._winvoice_order_item_meta_ps").children("._winvoice_order_item_meta_html_ps").last().find("select").removeAttr('name');
                    $("._winvoice_order_item_meta_ps").children("._winvoice_order_item_meta_html_ps").last().find("input").val('');
                    $("._winvoice_order_item_meta_ps").children("._winvoice_order_item_meta_html_ps").last().find("select").val('');
                    //$("._winvoice_order_item_meta").children("._winvoice_order_item_meta_html_ps").last().append('<a href="#" class="_winvoice-delete-order-item-meta"><span class="dashicons dashicons-trash" style="font-size:37px;color:#D94D40"></span></a>');
                    //$('._winvoice-add-order-item-meta .dashicons-plus-alt').css("margin-left","-20px");
                }
            );
            $(document).on(
                'click', '._winvoice-delete-order-item-meta-ps', function (e) {
                    e.preventDefault();
                    $(this).parent().remove();
                    if($("._winvoice_order_item_meta_html_ps").length==="1") {
                        //$('._winvoice-add-order-item-meta .dashicons-plus-alt').css("margin-left","0px");
                    }
                }
            );
            //////////////////////////////////////////////////////////////
            // End : display Order Item Meta for packing slip.
            //////////////////////////////////////////////////////////////



            /* Shipping Label Meta*/

            $(document).on(
                'change', '._winvoice-shipping-label-meta', function (e) {
                    // e.preventDefault();
                    // var value=$(this).val();
                    // $(this).attr("name",value+"_winvoice_shipping_label_meta_name");
                    // $(this).prev("._winvoice-product-order-meta-label").attr("name",value+"_winvoice_shipping_label_meta_label");
                }
            );

            var LabelmetaOrderHTML =$("._winvoice_shipping_label_meta_html").first().clone();

            $(document).on(
                'click', '._winvoice-add-shipping-label-meta', function (e) {
                    e.preventDefault();
                    console.log(LabelmetaOrderHTML);
                    $(this).siblings("._winvoice_shipping_label_meta").append(LabelmetaOrderHTML.clone());
                    // $("._winvoice_shipping_label_meta").children("._winvoice_shipping_label_meta_html").last().find("input").removeAttr('name');
                    // $("._winvoice_shipping_label_meta").children("._winvoice_shipping_label_meta_html").last().find("select").removeAttr('name');
                    // $("._winvoice_shipping_label_meta").children("._winvoice_shipping_label_meta_html").last().find("input").val('');
                    // $("._winvoice_shipping_label_meta").children("._winvoice_shipping_label_meta_html").last().find("select").val('');
                    //$("._winvoice_shipping_label_meta").children("._winvoice_shipping_label_meta_html").last().append('<a href="#" class="_winvoice-delete-order-meta"><span class="dashicons dashicons-trash" style="font-size:37px;color:#D94D40"></span></a>');
                    //$('._winvoice-add-order-meta .dashicons-plus-alt').css("margin-left","-20px");
                }
            );

            $(document).on(
                'click', '._winvoice-delete-shipping-label-meta', function (e) {
                    e.preventDefault();
                    $(this).parent().remove();
                    if($("._winvoice_shipping_label_meta_html").length==="1") {
                        //$('._winvoice-add-order-meta .dashicons-plus-alt').css("margin-left","0px");
                    }
                }
            );




            $('.wpifw_dimension').selectize(
                {
                    plugins: ['remove_button'],
                    render: {
                        item: function (data, escape) {
                            return '<div class="item wpifw_dimension_selector">'+ escape(data.text) + '</div>';
                        }
                    },
                    onInitialize: function () {
                        var selectize = this;
                        $.ajax(
                            {
                                url: wpifw_ajax_obj.wpifw_ajax_url,
                                type: 'post',
                                data: {
                                    _ajax_nonce: wpifw_ajax_obj.nonce,
                                    action: "wpifw_get_product_dimension_show",
                                },
                                success: function (response) {
                                    var selected_dimension_items = response.data;
                                    selectize.setValue(selected_dimension_items);
                                }
                            }
                        );
                    }
                }
            );

            $(document).on(
                'change', '.wpifw_dimension', function (e) {
                    e.preventDefault();
                    $.ajax(
                        {
                            url: wpifw_ajax_obj.wpifw_ajax_url,
                            type: 'post',
                            data: {
                                _ajax_nonce: wpifw_ajax_obj.nonce,
                                action: "wpifw_invoice_product_dimension_show",
                                dimension: $(".wpifw_dimension").val()
                            },
                            success: function (response) {
                            }
                        }
                    );
                }
            );

            /*$('.wpifw_template_ancient_color').wpColorPicker();*/

            /*$(document).on('click', '._winvoice-invoice-delete', function (e) {
            e.preventDefault();
            if (confirm("All the invoices are deleted!")) {
                $.ajax({
                    url: wpifw_ajax_obj.wpifw_ajax_url,
                    type: 'post',
                    data: {
                        _ajax_nonce: wpifw_ajax_obj.nonce,
                        action: "wpifw_delete_all_invoice"
                    },
                    success: function (response) {
                    }
                });
            }
            });*/


            $(document).on(
                'click', '.woo-pdf-review-notice ul li a', function (e) {
                    e.preventDefault();
                    let notice = $(this).attr('val');

                    if(notice==="given") {
                        window.open('https://wordpress.org/plugins/webappick-pdf-invoice-for-woocommerce/reviews/?rate=5#new-post','_blank');
                    }
                    $(".woo-pdf-review-notice").slideUp(200, "linear");

                    $.ajax(
                        {
                            url: wpifw_ajax_obj.wpifw_ajax_url,
                            type: 'post',
                            data: {
                                _ajax_nonce: wpifw_ajax_obj.nonce,
                                action: "wpifw_save_review_notice",
                                notice: notice
                            },
                            success: function (response) {
                            }
                        }
                    );
                }
            );


            $(document).on(
                'click', '._winvoice-template-selection', function (e) {
                    e.preventDefault();
                    let template = $(this).data('template');
                    $('#winvoiceModalTemplates').modal('hide');
                    $("body").removeClass(
                        function (index, className) {
                            return (className.match(/\S+-modal-open(^|\s)/g) || []).join(' ');
                        }
                    );
                    $('div[class*="-modal-backdrop"]').remove();
                    $(this).find('img').removeClass('_winvoice-template');
                    $(this).find('img').addClass('_winvoice-slected-template');
                    $(this).parent().siblings().find('img').removeClass('_winvoice-slected-template').addClass('_winvoice-template');
                    $.ajax(
                        {
                            url: wpifw_ajax_obj.wpifw_ajax_url,
                            type: 'post',
                            data: {
                                _ajax_nonce: wpifw_ajax_obj.nonce,
                                action: "wpifw_save_pdf_template",
                                template: template
                            },
                            success: function (response) {
                              console.log(response)
                                $('._winvoice-template-preview').attr('src',response.data+'.png');
                                $('.invoice_template_preiview_btn').attr('href',response.data+'.png');

                            }
                        }
                    );

                }
            );


            $(document).on(
                'click', '._winvoice-stamp-selection', function (e) {
                    e.preventDefault();

                    let stamp = $(this).data('stamp');
                    $('#modalStamps').modal('hide');

                    $("body").removeClass(
                        function (index, className) {
                            return (className.match(/\S+-modal-open(^|\s)/g) || []).join(' ');
                        }
                    );
                    $('div[class*="-modal-backdrop"]').remove();

                    $.ajax(
                        {
                            url: wpifw_ajax_obj.wpifw_ajax_url,
                            type: 'post',
                            data: {
                                _ajax_nonce: wpifw_ajax_obj.nonce,
                                action: "wpifw_save_paid_stamp",
                                stamp: stamp
                            },
                            success: function (response) {

                                $('._winvoice-stamp-preview').attr('src',response.data+'.png');

                            }
                        }
                    );

                }
            );

            if($('#wpifw_paid_stamp').is(':checked') ) {

                $('._winvoice-stamp-btn').removeClass('_winvoice-disable');
                $('.wpifw_paid_stamp_opacity').removeClass('_winvoice-disable');
                $('._winvoice_custom_stamp_preview').removeClass('_winvoice-disable');
                $('#wpifw_upload_custom_stamp_button').removeClass('_winvoice-disable');
                $('._winvoice-stamp-preview').css('display','block');

            } else {

                $('._winvoice-stamp-btn').addClass('_winvoice-disable');
                $('.wpifw_paid_stamp_opacity').addClass('_winvoice-disable');
                $('._winvoice_custom_stamp_preview').addClass('_winvoice-disable');
                $('#wpifw_upload_custom_stamp_button').addClass('_winvoice-disable');
                $('._winvoice-stamp-preview').css('display','none');

            }

            $(document).on(
                'click', '#wpifw_paid_stamp', function () {
                    if($(this).is(':checked') ) {

                        $('#modalStamps').modal('show');
                        $('._winvoice-stamp-btn').removeClass('_winvoice-disable');
                        $('.wpifw_paid_stamp_opacity').removeClass('_winvoice-disable');
                        $('._winvoice_custom_stamp_preview').removeClass('_winvoice-disable');
                        $('#wpifw_upload_custom_stamp_button').removeClass('_winvoice-disable');
                        $('._winvoice-stamp-preview').css('display','block');

                        $.ajax(
                            {
                                url: wpifw_ajax_obj.wpifw_ajax_url,
                                type: 'post',
                                data: {
                                    _ajax_nonce: wpifw_ajax_obj.nonce,
                                    action: "wpifw_paid_stamp_enabled"
                                },
                                success: function (response) {

                                    /*$('._winvoice-stamp-preview').attr('src','../wp-content/plugins/webappick-pdf-invoice-for-woocommerce-pro/admin/images/'+response.data+'.png');*/

                                }
                            }
                        );

                    } else {

                        $('._winvoice-stamp-btn').addClass('_winvoice-disable');
                        $('.wpifw_paid_stamp_opacity').addClass('_winvoice-disable');
                        $('._winvoice_custom_stamp_preview').addClass('_winvoice-disable');
                        $('#wpifw_upload_custom_stamp_button').addClass('_winvoice-disable');
                        $('._winvoice-stamp-preview').css('display','none');

                        $.ajax(
                            {
                                url: wpifw_ajax_obj.wpifw_ajax_url,
                                type: 'post',
                                data: {
                                    _ajax_nonce: wpifw_ajax_obj.nonce,
                                    action: "wpifw_paid_stamp_disabled"
                                },
                                success: function (response) {

                                    /*$('._winvoice-stamp-preview').attr('src','../wp-content/plugins/webappick-pdf-invoice-for-woocommerce-pro/admin/images/'+response.data+'.png');*/

                                }
                            }
                        );

                    }
                }
            );

            $(document).on(
                'click', '#wpifw_enable_signature', function () {
                    if($(this).is(':checked') ) {

                        $('._winvoice-signature-btn').removeClass('_winvoice-disable');
                        $('._winvoice-signature-preview').removeClass('_winvoice-disable');

                        $.ajax(
                            {
                                url: wpifw_ajax_obj.wpifw_ajax_url,
                                type: 'post',
                                data: {
                                    _ajax_nonce: wpifw_ajax_obj.nonce,
                                    action: "wpifw_signature_enabled"
                                },
                                success: function (response) {

                                    // $('._winvoice-stamp-preview').attr('src','../wp-content/plugins/webappick-pdf-invoice-for-woocommerce-pro/admin/images/'+response.data+'.png');

                                }
                            }
                        );

                    } else {

                        $('._winvoice-signature-btn').addClass('_winvoice-disable');
                        $('._winvoice-signature-preview').addClass('_winvoice-disable');

                        $.ajax(
                            {
                                url: wpifw_ajax_obj.wpifw_ajax_url,
                                type: 'post',
                                data: {
                                    _ajax_nonce: wpifw_ajax_obj.nonce,
                                    action: "wpifw_signature_disabled"
                                },
                                success: function (response) {
                                    // $('._winvoice-stamp-preview').attr('src','../wp-content/plugins/webappick-pdf-invoice-for-woocommerce-pro/admin/images/'+response.data+'.png');

                                }
                            }
                        );

                    }
                }
            );

            //--------- Close Logo. -------------//
            $(document).on(
                'click', '.wpifw_close_logo', function () {
                    $("#wpifw_logo_attachment_id").val('');
                    $('#logo_assets').hide('fast')
                }
            );

            //--------- Close Custom Stamp. -------------//
            $(document).on(
                'click', '.wpifw_close_custom_stamp', function () {
                    $("#wpifw_custom_stamp_attachment_id").val('');
                    $('#custom_stamp_assets').hide('fast')
                }
            );

            //--------- Close Signature. -------------//
            $(document).on(
                'click', '.wpifw_close_signature', function () {
                    $("#wpifw_signature_attachment_id").val('');
                    $('#signature_assets').hide('fast')
                }
            );

            //--------- Close Background. -------------//
            $(document).on(
                'click', '.wpifw_invoice_close_background', function () {
                    $("#wpifw_invoice_background_attachment_id").val('');
                    $('#wpifw_invoice_background').hide('fast')
                }
            );

            $(document).on(
                'change', '#wpifw_invoice_number_type', function () {
                    var value = $(this).val();
                    if(value === "pre_custom_number_suf") {
                        $('._winvoice-next-invoice, ._winvoice-invoice-prefix, ._winvoice-invoice-suffix').removeClass('_winvoice-hidden');
                        $('._winvoice-next-invoice, ._winvoice-invoice-prefix, ._winvoice-invoice-suffix').css('display','block');
                    } else if(value === "pre_order_number_suf") {
                        $('._winvoice-next-invoice, ._winvoice-invoice-prefix, ._winvoice-invoice-suffix').removeClass('_winvoice-hidden');
                        $('._winvoice-next-invoice, ._winvoice-invoice-prefix, ._winvoice-invoice-suffix').css('display','block');
                        $('._winvoice-next-invoice').css('display','none');
                    } else if (value === "order_number") {
                        $('._winvoice-next-invoice, ._winvoice-invoice-prefix, ._winvoice-invoice-suffix').removeClass('_winvoice-hidden');
                        $('._winvoice-next-invoice, ._winvoice-invoice-prefix, ._winvoice-invoice-suffix').css('display','none');
                    }

                    var activeSideTab = sessionStorage.getItem('activeSidebarTab');
                    $('._winvoice-dashboard-sidebar ._winvoice-sidebar-navbar-light').height($(contents[activeSideTab]).parent().height()-23);
                }
            );

            var invoice_custom_paper_size = $("select[name='wpifw_invoice_paper_size']").val();
            if(invoice_custom_paper_size === "custom") {
                $(".wpifw-invoice-paper-size-label").text("Paper Size(mm)").css("vertical-align","top");
                $(".wpifw_invoice_custom_paper_size").css('display','block');
                $("input[name='wpifw_invoice_custom_paper_wide'], input[name='wpifw_invoice_custom_paper_height']").attr("required","required");
            } else {
                $(".wpifw-invoice-paper-size-label").text("Paper Size").css("vertical-align","middle");
                $(".wpifw_invoice_custom_paper_size").css('display','none');
                $("input[name='wpifw_invoice_custom_paper_wide'], input[name='wpifw_invoice_custom_paper_height']").removeAttr("required");
            }
            $(document).on(
                'change', "select[name='wpifw_invoice_paper_size']", function () {
                    var value = $(this).val();
                    if(value === "custom") {
                        $(".wpifw-invoice-paper-size-label").text("Paper Size(mm)").css("vertical-align","top");
                        $(".wpifw_invoice_custom_paper_size").slideDown();
                        $("input[name='wpifw_invoice_custom_paper_wide'], input[name='wpifw_invoice_custom_paper_height']").attr("required","required");
                    } else {
                        $(".wpifw-invoice-paper-size-label").text("Paper Size").css("vertical-align","middle");
                        $(".wpifw_invoice_custom_paper_size").slideUp();
                        $("input[name='wpifw_invoice_custom_paper_wide'], input[name='wpifw_invoice_custom_paper_height']").removeAttr("required");
                    }

                }
            );

            var packingslip_custom_paper_size = $("select[name='wpifw-pickingslip-paper-size']").val();
            if(packingslip_custom_paper_size === "custom") {
                $(".wpifw-packingslip-paper-size-label").text("Paper Size(mm)").css("vertical-align","top");
                $(".wpifw_packingslip_custom_paper_size").css('display','block');
                $("input[name='wpifw_pickingslip_custom_paper_wide'], input[name='wpifw_pickingslip_custom_paper_height']").attr("required","required");
            } else {
                $(".wpifw-packingslip-paper-size-label").text("Paper Size").css("vertical-align","middle");
                $(".wpifw_packingslip_custom_paper_size").css('display','none');
                $("input[name='wpifw_pickingslip_custom_paper_wide'], input[name='wpifw_pickingslip_custom_paper_height']").removeAttr("required");
            }
            $(document).on(
                'change', "select[name='wpifw-pickingslip-paper-size']", function () {
                    var value = $(this).val();
                    console.log(value);
                    if(value === "custom") {
                        $(".wpifw-packingslip-paper-size-label").text("Paper Size(mm)").css("vertical-align","top");
                        $(".wpifw_packingslip_custom_paper_size").slideDown();
                        $("input[name='wpifw_pickingslip_custom_paper_wide'], input[name='wpifw_pickingslip_custom_paper_height']").attr("required","required");
                    } else {
                        $(".wpifw-packingslip-paper-size-label").text("Paper Size").css("vertical-align","middle");
                        $(".wpifw_packingslip_custom_paper_size").slideUp();
                        $("input[name='wpifw_pickingslip_custom_paper_wide'], input[name='wpifw_pickingslip_custom_paper_height']").removeAttr("required");
                    }

                }
            );

            var shipping_lebel_custom_paper_size = $("select[name='wpifw_shipping_lebel_paper']").val();
            if(shipping_lebel_custom_paper_size === "custom") {
                $(".wpifw-shipping-lebel-paper-size-label").text("Paper Size(mm)").css("vertical-align","top");
                $(".wpifw_shipping_lebel_custom_paper_size").css('display','block');
                $("input[name='wpifw_shipping_lebel_custom_paper_wide'], input[name='wpifw_shipping_lebel_custom_paper_height']").attr("required","required");
            } else {
                $(".wpifw-shipping-lebel-paper-size-label").text("Paper Size").css("vertical-align","middle");
                $(".wpifw_shipping_lebel_custom_paper_size").css('display','none');
                $("input[name='wpifw_shipping_lebel_custom_paper_wide'], input[name='wpifw_shipping_lebel_custom_paper_height']").removeAttr("required");
            }
            $(document).on(
                'change', "select[name='wpifw_shipping_lebel_paper']", function () {
                    var value = $(this).val();
                    console.log(value);
                    if(value === "custom") {
                        $(".wpifw-shipping-lebel-paper-size-label").text("Paper Size(mm)").css("vertical-align","top");
                        $(".wpifw_shipping_lebel_custom_paper_size").slideDown();
                        $("input[name='wpifw_shipping_lebel_custom_paper_wide'], input[name='wpifw_shipping_lebel_custom_paper_height']").attr("required","required");
                    } else {
                        $(".wpifw-shipping-lebel-paper-size-label").text("Paper Size").css("vertical-align","middle");
                        $(".wpifw_shipping_lebel_custom_paper_size").slideUp();
                        $("input[name='wpifw_shipping_lebel_custom_paper_wide'], input[name='wpifw_shipping_lebel_custom_paper_height']").removeAttr("required");
                    }

                }
            );

            $(document).on(
                'click', "._winvoice-next-invoice-no-reset", function (e) {
                    e.preventDefault();
                    $("._winvoice-next-invoice-no-reset").text("Resetting...");
                    $.ajax(
                        {
                            url: wpifw_ajax_obj.wpifw_ajax_url,
                            type: 'post',
                            data: {
                                _ajax_nonce: wpifw_ajax_obj.nonce,
                                action: "wpifw_next_invoice_no_reset"
                            },
                            success: function (response) {
                                $("input[name='wpifw_invoice_no']").val(response.data);
                                $("._winvoice-next-invoice-no-reset").text("Reset");
                            }
                        }
                    );

                }
            );

            if($('#wpifw_bulk_download_as').val() === "individual") {
                $('#wpifw_enable_compress_bulk_download').attr('checked', "checked");
            } else {
                $('#wpifw_enable_compress_bulk_download').removeAttr('checked');
            }

            $(document).on(
                'change', '#wpifw_bulk_download_as', function () {
                    if($(this).val() === "individual") {
                        $('#wpifw_enable_compress_bulk_download').attr('checked', "checked");
                    } else {
                        $('#wpifw_enable_compress_bulk_download').removeAttr('checked');
                    }
                }
            );


            if($('#wpifw_enable_packingslip_background').is(':checked') ) {

                $('._winvoice-packingslip-background-btn').removeClass('_winvoice-disable');
                $('.wpifw_packingslip_background_opacity').removeClass('_winvoice-disable');
                $('._winvoice-packingslip-background-preview').css('display','block');

            } else {

                $('._winvoice-packingslip-background-btn').addClass('_winvoice-disable');
                $('.wpifw_packingslip_background_opacity').addClass('_winvoice-disable');
                $('._winvoice-packingslip-background-preview').css('display','none');

            }

            $(document).on(
                'click', '#wpifw_enable_packingslip_background', function () {
                    if($(this).is(':checked') ) {

                        $('._winvoice-packingslip-background-btn').removeClass('_winvoice-disable');
                        $('.wpifw_packingslip_background_opacity').removeClass('_winvoice-disable');
                        $('._winvoice-packingslip-background-preview').css('display','block');

                        $.ajax(
                            {
                                url: wpifw_ajax_obj.wpifw_ajax_url,
                                type: 'post',
                                data: {
                                    _ajax_nonce: wpifw_ajax_obj.nonce,
                                    action: "wpifw_packingslip_background_enabled"
                                },
                                success: function (response) {
                                }
                            }
                        );

                    } else {

                        $('._winvoice-packingslip-background-btn').addClass('_winvoice-disable');
                        $('.wpifw_packingslip_background_opacity').addClass('_winvoice-disable');
                        $('._winvoice-packingslip-background-preview').css('display','none');

                        $.ajax(
                            {
                                url: wpifw_ajax_obj.wpifw_ajax_url,
                                type: 'post',
                                data: {
                                    _ajax_nonce: wpifw_ajax_obj.nonce,
                                    action: "wpifw_packingslip_background_disabled"
                                },
                                success: function (response) {
                                }
                            }
                        );

                    }
                }
            );

            $('#atttoorder').change(
                function () {
                    if(this.checked !== true) {
                        $('#emailAttechedData').css('display','none');
                    }
                    else{
                        $('#emailAttechedData').css('display','block');
                    }
                }
            );

            $('#wpifw_download').change(
                function () {
                    if(this.checked !== true) {
                        $('#downloadAttechedData').css('display','none');
                    }
                    else{
                        $('#downloadAttechedData').css('display','block');
                    }
                }
            );

            $('#atttoorder9').change(
                function () {
                    if(this.checked !== true) {
                        $('#addPageNumber').css('display','none');
                    }
                    else{
                        $('#addPageNumber').css('display','block');
                    }
                }
            );


            // Add page number option.
            $('#addPageNumber4').change(
                function () {
                    if(this.checked !== true) {
                        $("#customize_page_number_check").val('0')
                        $('.customize_page_number').css('display','none');
                    }
                    else{
                        $("#customize_page_number_check").val('1')
                        $('.customize_page_number').css('display','block');
                    }
                }
            );
            // Display customizable option for adding page number.
            let val = $("#customize_page_number_check").val();
            if( val == 1) {
                $('.customize_page_number').css('display','block');
            }else{
                $('.customize_page_number').css('display','none');
            }

        }
    );




    var WooInvoiceFoundation = function(){
        /**
         * Invoice product description hide/show.
         */
        var wpifw_invoice_product_description_toggle = function() {
            var defaultValue = $("#wpifw_product_description_show" ).val();
            if( defaultValue == 'none' ){
                $('#hideDescriptionLimit').hide();
            }
            $("#wpifw_product_description_show").on('change', function () {
                var desType = $(this).children("option:selected").val();
               if( desType == 'none' ){
                   $('#hideDescriptionLimit').hide();
               }else{
                   $('#hideDescriptionLimit').show();
               }
            });
        };

        /**
         * Packingslip product description hide/show.
         */
        var wpifw_packing_slip_product_description_toggle = function() {
            var defaultValue = $("#wpifw_packingslip_product_description_show" ).val();
            if( defaultValue == 'none' ){
                $('#wpifw_hide_packing_slip_description_limit').hide();
            }
            $("#wpifw_packingslip_product_description_show").on('change', function () {
                var desType = $(this).children("option:selected").val();
                if( desType == 'none' ){
                    $('#wpifw_hide_packing_slip_description_limit').hide();
                }else{
                    $('#wpifw_hide_packing_slip_description_limit').show();
                }
            });
        };

        /**
         * Bulk download css fields show hide option.
         */

        var wpifw_csv_fields_show_hide = function() {
            var defaultValue = $("#wpifw_bulk_download_type" ).on('select', function (){
                console.log(this.val())
            });
            // if( defaultValue == 'none' ){
            //     $('#wpifw_hide_packing_slip_description_limit').hide();
            // }
            // $("#wpifw_packingslip_product_description_show").on('change', function () {
            //     var desType = $(this).children("option:selected").val();
            //     if( desType == 'none' ){
            //         $('#wpifw_hide_packing_slip_description_limit').hide();
            //     }else{
            //         $('#wpifw_hide_packing_slip_description_limit').show();
            //     }
            // });
        };

        /**
         * Initialization all function.
         */
        return {
            initialHelper:function(){
                // Invoice product description hide/show.
                wpifw_invoice_product_description_toggle();
                // Packingslip product description hide/show.
                wpifw_packing_slip_product_description_toggle();

                //
                wpifw_csv_fields_show_hide();
            }
        }
    }(jQuery);

    /* jQuery ready  */
    jQuery(document).on('ready',function() {WooInvoiceFoundation.initialHelper();});


})(jQuery);
