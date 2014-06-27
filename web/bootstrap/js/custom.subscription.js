var Subscription = {
    init: function(Routing) {
        $('.addSubscription').each(function(i, el) {
            var $t = $(el),
                type = $t.data('type'),
                modal = $('#' + type + '-modal');

            $t.on('click', function (e) {
                e.preventDefault();
                modal.reveal({dismissmodalclass: 'closeModal'});
            });

            modal.on('click', '.saveBttn', function (e) {
                e.preventDefault();

                var form = modal.find('form').first(),
                    data = form.serialize(),
                    url = form.attr('action');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    success: function(data) {
                        $('#' + type + '-list').append(data.html);
                    }
                });
            });
        });
        $('.subscriptions').on('click', '.remove-subscription', function(e) {
            e.preventDefault();
            var $t = $(this);
            $.ajax({
                url: Routing.generate('subscription_remove', {id: $t.data('id')}),
                method: 'POST'
            });
            $(this).parents('li').remove();
        });
        $('#notify-me').on('change', function() {
            $.ajax({
                url: Routing.generate('subscribe_email'),
                method: 'POST',
                data: {value: $(this).prop('checked') ? 1 : 0}
            });
        });
    }
};