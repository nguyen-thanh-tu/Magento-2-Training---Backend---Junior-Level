define(['jquery', 'uiComponent', 'ko'], function ($, Component, ko) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'TUTJunior_HandlePageRedirection/notification'
            },
            initialize: function (config) {
                this._super();
                this.setProductData(config.items);
                var $win = $(window); // or $box parent container
                var $box = $(".salebox");

                $win.on("click.Bst", function(event){
                    if (
                        $box.has(event.target).length === 0 //checks if descendants of $box was clicked
                        &&
                        !$box.is(event.target) //checks if the $box itself was clicked
                    ){
                        $(".salebox-product").hide();
                    } else {
                        $(".salebox-product").show();
                    }
                });

                $(document).on('click', '.common-place-order', function (event) {
                    $.ajax({
                        method:"POST",
                        dataType: "json",
                        url:BASE_URL+"tjhpr/ajax/productsaleoff",
                        success: function (result){

                        }
                    });
                });
            },
            setProductData: function(items) {
                this.columnNames = ko.computed(function () {
                    if (items.length === 0)
                        return [];
                    var props = [];
                    var obj = items[0];
                    for (var name in obj)
                        props.push(name);
                    return props;
                });
            }
        });
    }
);
