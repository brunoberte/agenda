const global = {
    debug: true,
    base_url: '/'
};

log = function (s) {
    if (global.debug) {
        if (typeof(console.log) !== 'undefined') {
            console.log(s);
        }
    }
};

const app = (function ($) {

    function init() {
        mask_inputs();

        $('.btn-delete').on('click', function (e) {
            e.preventDefault();
            if (!confirm('Are you sure?')) {
                return false;
            }
            const id = $(this).data('id');
            $.ajax({
                url: '/api/contact/' + id,
                type: 'DELETE'
            })
            .done(function() {
                window.location.reload();
            })
            .fail(function() {
                alert( "error deleting record" );
            });
        });

        $('#formModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            const modal = $(this);

            const form = modal.find('form');
            form.hide();
            form[0].reset();

            if (id === '') {
                form.show();
                modal.find('.modal-title').text('Add Contact');
            } else {
                modal.find('.modal-title').text('Edit Contact');
                form.hide();
                $.ajax({
                    url: '/api/contact/' + id,
                    type: 'GET',
                    dataType: 'JSON'
                })
                .done(function(data){
                    const form = $('#formModal form');

                    form.find('input[name="id"]').val(data.id);
                    form.find('input[name="first_name"]').val(data.first_name);
                    form.find('input[name="last_name"]').val(data.last_name);
                    form.find('input[name="email"]').val(data.email);
                    form.find('input[name="main_number"]').val(data.main_number);
                    form.find('input[name="secondary_number"]').val(data.secondary_number);

                    form.show();
                })
                .fail(function() {
                    alert( "error loading record" );
                });

            }
        })

        $('#formModal .modal-footer .btn-primary').on('click', function (event) {
            const form = $('#formModal form');
            if (!form[0].checkValidity()) {
                alert('Please fill all mandatory fields');
                return;
            }

            $(this).attr('disabled', 'disabled');

            const id = form.find('input[name="id"]').val();

            $.ajax({
                url: '/api/contact/' + id,
                type: id === '' ? 'POST' : 'PUT',
                dataType: 'JSON',
                data: form.serialize()
            }).done(function(data){
                window.location.reload();
            }).fail(function(jqXHR){
                $('#formModal .modal-footer .btn-primary').attr('disabled', false);
                data = jqXHR.responseJSON;
                if (data.errors !== undefined) {
                    alert(data.errors.join("\n"));
                } else {
                    alert('Undefined error. Please try again');
                }
            })
        });
    }

    return {
        init: init,
    }

})(jQuery);

$(document).ready(function () {
    app.init();
});


function mask_inputs() {

    const SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function (val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };

    $('.format_phone').mask(SPMaskBehavior, spOptions);
}
