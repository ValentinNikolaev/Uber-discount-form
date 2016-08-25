$( document ).ready(function( $ ) {

    $(document).ajaxError(function () {
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



    $("#find_form").on("submit", function(event) {
        event.preventDefault();
        event.stopPropagation();
        var form = $(this);
        var formData = new FormData($(this)[0]);
        form
            .disableButtons()
            .addLoadingSpin();

        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: formData,
            //Options to tell jQuery not to process data or worry about content-type
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                alert("success");
            }
        }).always(function () {
            form
                .enableButtons()
                .removeLoadingSpin();
        });
    });

});