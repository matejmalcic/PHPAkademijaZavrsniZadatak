$(document).ready(function() {
    //Modal for putting product in cart
    $('i.fa-cart-plus').on('click', function () {
        var productId = $(this).attr('data-productId');
        var productName = $(this).attr('data-productName');

        $('#question').html('Add ' + productName + ' to cart ?');
        $('#addLink').attr('href', '/~polaznik20/product/putProductInCart?productId=' + productId);
        $('#myModal').modal('show');

        return false;
    });

    $('#addLink').click(function(){
        var amount = $('#amount').val();
        var link = $(this).attr('href')+'&amount=' + amount;

        $('#addLink').attr('href', link);
    });

    //Modal for change product amount
    $('.changeAmountModal').on('click', function () {
        var productId = $(this).attr('data-productId');
        var cartId = $(this).attr('data-cartId');

        $('#question').html('Change order');
        $('#addLink').attr('href', '/~polaznik20/cart/changeAmount?productId=' + productId + '&cartId=' + cartId);
        $('#myModal').modal('show');

        return false;
    });

    $('#addLink').click(function(){
        var amount = $('#amount').val();
        var link = $(this).attr('href')+'&amount=' + amount;

        $('#addLink').attr('href', link);
    });
});