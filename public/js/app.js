$(document).ready(() => {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    });

    let searchNode = $('nav form.search-nodes');
    let searchAddress = $('nav form.search-addresses');
    let changeEmail = $('#change-email').closest('form');

    searchNode.on('submit', event => {
        event.preventDefault();

        let search = searchNode.find('input').val().trim();

        window.location.href = `/nodes/${search}`;
    });

    searchAddress.on('submit', event => {
        event.preventDefault();

        let search = searchAddress.find('input').val().trim();

        window.location.href = `/addresses/${search}`;
    });

    changeEmail.on('submit', event => {
        event.preventDefault();

        let data = {
            email: changeEmail.find('input[name="change-email"]').val(),
            addressId: changeEmail.find('input[name="addressId"]').val(),
        };

        let feedback = $('#change-email-feedback');
        let feedbackClass = 'valid-feedback';

        feedback.removeClass().hide();
        $('#change-email-help').hide();

        $.ajax({
            method: 'post',
            url: '/addresses/change-email',
            data: data,
            dataType: 'json',

            success: function (response) {
                feedback.text(response.data).addClass(feedbackClass).show();
            },

            error: function (response) {
                let errorMessage = Object.values(response.responseJSON.message)[0][0];
                feedbackClass = 'in' + feedbackClass;

                feedback.text(errorMessage).addClass(feedbackClass).show();
            },
        });
    });
});
