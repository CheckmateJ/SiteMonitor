const $ = require('jquery');

$(".advance-test").click(function (e) {
    e.preventDefault();
    const id = $(this).data('id')

        $.ajax({
            type: 'get',
            url: `/site/test/${id}/run`,
            success: function (status) {
                alert(JSON.stringify(status))
            }
        }
    )
});