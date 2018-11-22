$('.remove-user').click(function () {
    let id = $(this).data('value')
    let username = $(this).data('name')
    let message = 'you want to delete user "' + username + '"'
    swal({
        title: "Are you sure?",
        text: message,
        type: "warning",
        showCancelButton: true,
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
    }).then(function () {
        $.ajax({
            url: 'profile/delete?id=' + id,
            method: 'DELETE',
            dataType: 'json',
            async: true,
            data: {body: id, token: ''},
            success: function (response) {
                if (response.status == 200) {
                    swal({
                        title: "Done!",
                        text: "User " + username + ' has been removed successfully',
                        imageUrl: 'thumbs-up.jpg'
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 2000)

                }
            }
        })


    });


})