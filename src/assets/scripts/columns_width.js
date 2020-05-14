(function( $ ) {

    $(document).ready(function($) {
        let custom = window.custom;
        let acf = window.acf;

        if (typeof acf == "undefined") {
            return;
        }

        const thumbs_dir = custom.stylesheet_directory + "/thumbs/";
        const swatches_dir = custom.stylesheet_directory + "/swatches/";

        acf.add_action("append_field/type=flexible_content", function($el) {
            changeOptions($el, "append");
        });

        acf.add_action("remove_field/type=flexible_content", function($el) {
            changeOptions($el, "remove");
        });

        acf.add_action("load", function($el) {
            populate($el);
        });

        function populate( el ) {
            const column_rows = getColumnRowsCount();

            if ($("#columns_count").length === 0) {
                $("<input>")
                    .attr({
                        type: "hidden",
                        id: "columns_count",
                        name: "columns_count",
                        value: column_rows,
                    })
                    .appendTo($("#post"));
            }

            populateBgInputs(el);
            populateColumnsLayoutWidth(el);
            populateColumnsLayoutMobileOrder();
        }

        /* Populate inputs for bg color */
        function populateBgInputs(el) {
            const colors = {
                "bg-grey-light" : "cfcfcf",
                "bg-grey-dark" : "838383",
            };
            const background_color_rows = el.find('div[data-name="option_background_color"]');

            $.each(background_color_rows, function() {

                const color_options = $(this)
                    .children(".acf-input")
                    .children(".acf-radio-list")
                    .children("li");

                $.each(color_options, function() {

                    const color_input = $(this).children("label").children("input");
                    const input_html = color_input[0].outerHTML;
                    const input_value = color_input.val();

                    const color_key = colors[input_value];
                    const swatch_html = '<img class="swatches" src="' + swatches_dir + color_key + '.png" swatch"/>';

                    color_input.parent("label").html( input_html + swatch_html );

                })

            });
        }

        /* Populate inputs for columns width */
        function populateColumnsLayoutWidth(el) {
            const column_rows = el.find('div[data-name="columns"]').slice(1);

            const page_id = $("#post_ID").val();
            let current_values = [];

            $.ajax({

                url: custom.ajax_url,
                type: "post",
                async: false,
                ContentType: "application/json",

                data: {
                    action: "get_width_values",
                    page_id: page_id,
                    columns_count: column_rows.length,
                },

                success: function(html) {
                    current_values = JSON.parse(html);
                },

            });

            $.each(column_rows, function(counter) {

                let node_length = $(this)
                    .children(".acf-input")
                    .children(".acf-repeater")
                    .children(".acf-table")
                    .children("tbody")
                    .children("tr").length;

                node_length -= 1;

                let options = [];
                let thumb = [];

                switch (node_length) {

                    case 1:

                        options = ["10", "8"];

                        thumb = [
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '10.png" alt="10 thumb"/>',
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '8.png" alt="8 thumb"/>',
                        ];

                        break;

                    case 2:

                        options = ["7_5", "5_7", "6_6"];

                        thumb = [
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '7_5.png" alt="7_5 thumb"/>',
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '5_7.png" alt="5_7 thumb"/>',
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '6_6.png" alt="6_6 thumb"/>',
                        ];

                        break;

                    case 3:

                        options = ["6_3_3", "3_3_6", "4_4_4"];

                        thumb = [
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '6_3_3.png" alt="6_3_3 thumb"/>',
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '3_3_6.png" alt="3_3_6 thumb"/>',
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '4_4_4.png" alt="4_4_4 thumb"/>',
                        ];

                        break;

                    case 4:

                        options = ["3_3_3_3"];

                        thumb = [
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '3_3_3_3.png" alt="3_3_3_3 thumb"/>',
                        ];

                        break;

                }

                const ul = $(this)
                    .parents("div.acf-fields")
                    .children('div[data-name="option_columns_width"]')
                    .find("ul.acf-radio-list");

                ul.empty();

                for (let i = 0; i < options.length; i++) {

                    const value = current_values[counter];
                    let li = '';

                    if (value === options[i]) {

                        li =
                            '<li><label class="selected"><input type="radio" name="columns_width_' +
                            counter +
                            '" value="' +
                            options[i] +
                            '" checked="checked">' +
                            thumb[i] +
                            "</label></li>";

                    } else {

                        li =
                            '<li><label><input type="radio" name="columns_width_' +
                            counter +
                            '" value="' +
                            options[i] +
                            '">' +
                            thumb[i] +
                            "</label></li>";
                    }

                    ul.prepend(li);

                }

            });
        }

        /* Populate options for columns order configuration */
        function populateColumnsLayoutMobileOrder(el) {
            let column_rows = [];

            if ( typeof el !== 'undefined' ) {
                column_rows = el.find('div[data-name="columns"]');
            } else {
                column_rows = $('div[data-name="columns"]').slice(1);
            }

            let node_length = 0;

            $.each(column_rows, function() {
                node_length = $(this)
                    .find(".acf-table")
                    .children("tbody")
                    .children("tr").length;

                node_length -= 1;

                const mobile_order_option = $(this)
                    .parents("div.acf-fields")
                    .children('div[data-name="option_columns_mobile_order"]');

                const order_input = mobile_order_option.find('input[name*="option_columns_mobile_order"]');
                let order_input_value = order_input.val();
                let order = order_input_value ? order_input_value.split('_') : [];

                // prevent situation when column exists but order input is empty, i.e. when column created automatically via add template button
                if ( !order.length && node_length ) {
                    order = [1];
                    order_input_value = 1;
                    order_input.val(1)
                }

                const ul = $('<ul class="acf-radio-list acf-hl"></ul>');

                order.forEach((item) => {
                    ul.append("<li id='sort_" + item + "'>" + item + "</li>")
                });

                mobile_order_option.append(ul);

                /* Init sortable functionality */
                ul.sortable({
                    placeholder: "sortable-placeholder",
                    sort: function() {

                    },
                    update: function() {
                        const order = $(this)
                            .sortable('toArray')
                            .map((item) => {
                                    return item.split('_')[1];
                                }
                            );

                        order_input.val(order.join('_'));
                    },
                });

                if (node_length > 1) {
                    mobile_order_option.show();
                } else {
                    mobile_order_option.hide();
                }
            });
        }

        function changeOptions( el, action ) {
            $("input#columns_count").val(getColumnRowsCount());

            columnLayoutOptionsChange(el, action);
            columnLayoutMobileOrderOptionsChange(el, action);
        }

        function columnLayoutMobileOrderOptionsChange(el, action) {
            let column_count = parseInt( el
                .parents(".acf-fields")
                .prevAll(".acf-fc-layout-handle")
                .find(".acf-fc-layout-order")
                .text()
            );

            let node_length = el
                .parents(".acf-table")
                .children("tbody")
                .children("tr").length;

            if ( !isNaN(column_count) ) {
                const mobile_order_option = el
                    .parents("div.acf-fields")
                    .children('div[data-name="option_columns_mobile_order"]');

                const order_input = mobile_order_option.find('input[name*="option_columns_mobile_order"]');
                const order_input_value = order_input.val();

                let order = order_input_value ? order_input_value.split('_') : [];
                let ul = mobile_order_option.find("ul.acf-radio-list");

                if (ul.length) {
                    if (action === "remove") {
                        node_length -= 2;
                        ul.empty();

                        const new_order = order.filter((item) => {
                            return item <= node_length;
                        });

                        if (new_order.length) {
                            const li = [];

                            new_order.forEach((order) => {
                                li.push($('<li id="sort_' + order + '">' + order + '</li>'))
                            });

                            ul.append(li);

                            order_input.val(new_order.join('_'));
                        } else {
                            order_input.val("");
                        }
                    } else {
                        node_length -= 1;

                        order.push(node_length);
                        order_input.val(order.join('_'));

                        const li = '<li id="sort_' + node_length + '">' + node_length + '</li>';
                        ul.append(li);
                    }

                    if (node_length > 1) {
                        mobile_order_option.show();
                    } else {
                        mobile_order_option.hide();
                    }
                } else {
                    populateColumnsLayoutMobileOrder(mobile_order_option.closest('.layout'));
                }
            }
        }

        function columnLayoutOptionsChange(el, action) {
            let node_length = el
                .parents(".acf-table")
                .children("tbody")
                .children("tr").length;

            let column_count = parseInt( el
                .parents(".acf-fields")
                .prevAll(".acf-fc-layout-handle")
                .find(".acf-fc-layout-order")
                .text()
            );

            if ( !isNaN( column_count ) ) {

                if (action === "remove") {
                    node_length -= 2;
                } else {
                    node_length -= 1;
                }

                let options = [];
                let thumb = [];

                switch (node_length) {

                    case 1:

                        options = ["10", "8"];

                        thumb = [
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '10.png" alt="10 thumb"/>',
                            '<img class="thumbs" src="' + thumbs_dir + '8.png" alt="8 thumb"/>',
                        ];

                        break;

                    case 2:

                        options = ["7_5", "5_7", "6_6"];

                        thumb = [
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '7_5.png" alt="7_5 thumb"/>',
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '5_7.png" alt="5_7 thumb"/>',
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '6_6.png" alt="6_6 thumb"/>',
                        ];

                        break;

                    case 3:

                        options = ["6_3_3", "3_3_6", "4_4_4"];

                        thumb = [
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '6_3_3.png" alt="6_3_3 thumb"/>',
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '3_3_6.png" alt="3_3_6 thumb"/>',
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '4_4_4.png" alt="4_4_4 thumb"/>',
                        ];

                        break;

                    case 4:

                        options = ["3_3_3_3"];

                        thumb = [
                            '<img class="thumbs" src="' +
                            thumbs_dir +
                            '3_3_3_3.png" alt="3_3_3_3 thumb"/>',
                        ];

                        break;

                }

                let ul = el
                    .parents("div.acf-fields")
                    .children('div[data-name="option_columns_width"]')
                    .find("ul.acf-radio-list");

                ul.empty();

                column_count -= 1;

                for (let i = 0; i < options.length; i++) {
                    const li =
                        '<li><label><input type="radio" checked name="columns_width_' +
                        column_count +
                        '" value="' +
                        options[i] +
                        '" >' +
                        thumb[i] +
                        "</label></li>";

                    ul.prepend(li);

                }

            }
        }

        function getColumnRowsCount() {
            return $('div[data-name="columns"]').slice(1).length;
        }
    });

})( jQuery );
