$(document)
    .ready(function ($) {
        var autocomplete = [];
        var form = $("#find_form");
        var ids = ['start_location', 'end_location'];
        var geo_location = ['lng', 'lat'];
        
        /**
         * Some initialize
         */
        (function () {
            $("input", form).each(function () {
                var inputId = $(this).attr("id");
                $.each(['lng', 'lat'], function (key, value) {
                    form.append("<input type='hidden' name='" + inputId + "_" + value + "'>")
                });
            });

            ids.forEach(attachAutocomplete);
        })();

        /**
         * attach Google autocomplete to our inputs
         * @param id
         */
        function attachAutocomplete(id) {
            var input = $("#" + id)[0];
            autocomplete[id] = new google.maps.places.Autocomplete(input);

            google.maps.event.addListener(autocomplete[id], 'place_changed', function () {
                var place = autocomplete[id].getPlace();
                $.each(['lng', 'lat'], function (key, value) {
                    var inputName = id + '_' + value;
                    var input = $("[name=" + inputName + "]");
                    if (input.length > 0) {
                        var placeValue = place.geometry.location[value];
                        console.log(placeValue);
                        if (typeof placeValue !== 'undefined') {
                            input.val(placeValue)
                        } else {
                            input.val('');
                        }
                    }
                });

            });

        }

        form.on("submit", function (event) {
            event.preventDefault();
            event.stopPropagation();
            var form = $(this);

            form
                .disableButtons()
                .addLoadingSpin();

            $.ajax({
                url: form.attr('action'),
                type: 'get',
                data: form.serialize(),
                success: function (response) {
                    $("#response").html(response);
                }
            }).always(function () {
                form
                    .enableButtons()
                    .removeLoadingSpin();
            });
        });
    })
    .ajaxError(function () {
        alert("Error during ajax request. Try again later.");
    });


$.fn.extend({
    disableButtons: function () {
        return this.each(function () {
            $(this)
                .find("[type='submit']")
                .each(function () {
                    $(this)
                        .attr("disabled", true)
                        .addClass("disabled disableButtons")
                })
            ;
        })
    },
    enableButtons: function () {
        return this.each(function () {
            $(this)
                .find("[type='submit'].disableButtons")
                .each(function () {
                    $(this)
                        .attr("disabled", false)
                        .removeClass("disabled disableButtons")
                })
            ;
        })
    },
    addLoadingSpin: function () {
        return this.each(function () {
            $(this).addClass("grid-loading")
        })
    },
    removeLoadingSpin: function () {
        return this.each(function () {
            $(this).removeClass("grid-loading")
        })
    }
});
