(function($) {

    var defaults = {
        ajax_url: '/ajax/'  // путь к папке скрипта для ajax
    };

    $.Cart = {

        init: function(settings)
        {
            $.extend(defaults, settings);
            this.settings = defaults;

            $('.btn-add-to-cart').click(function() {
                $.Cart.actionAdd(this);
            });

            return this;
        },

        actionAdd: function(context) {
            $.post($.Cart.settings.ajax_url + 'cart/add', {
                id : $(context).data('id')
            }, function(data) {
                if (data.success) {
                    $('#content-cart-top').html(data.content);
                    //alert('Товар добавлен в корзину.');
                }
            }, 'json');
        }

    }; // Cart

})(jQuery);


$(function() {

    $('#delivery_id').change(function() {
        $.post('/ajax/cart/checkout', {
            delivery_id : $('#delivery_id').val()
        }, function(data) {
            $('.delivery_address').html(data.content);
        }, 'json');
    });

});
