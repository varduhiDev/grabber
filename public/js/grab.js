$(document).ready(function () {
   $('#getArticles').click(function () {
       var that = $(this);
       that.prop('disabled', true);
       that.html('<i class="fa fa-spinner fa-spin"></i>Loading');
       $.ajax({
           url: '/getArticles',
           type: 'GET',
           complete: function() {
               that.prop('disabled', false);
               that.html('Get Articles');
           }
       });
   })
    $(document).on('click', '.deleteArticle', function () {
        var id = $(this).attr('data-id');
        $('#deleteArticle').attr('data-id', id);
    })
    $('#deleteArticle').click(function () {
        var _token = $('meta[name="csrf-token"]').attr('content'),
            id = $(this).attr('data-id');
        $.ajax({
            url: '/article/destroy',
            type: 'POST',
            data: {id : id, _token :_token},
            complete: function() {
               window.location.reload();
            }
        });
    })
})
