const $ = require('jquery');

$(".run-advance-test").click(function (e) {
    $(".modal-body").html("");
    const id = $(this).data('id')

        $.ajax({
            type: 'get',
            url: `/site/test/${id}/run`,
            success: function (status) {
                $(".modal-body").text(JSON.stringify(status));
            }
        }
    )
});

