let formContent = $('#contact-us')

$(document).ready(function () {
    validation()
})

function validation() {
    return formContent.validate({
        rules: {
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            title: {
                required: true,
            },
            message: {
                required: true
            }
        }
    })
}