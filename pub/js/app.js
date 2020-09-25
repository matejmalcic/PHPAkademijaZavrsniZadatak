$(document).ready(function() {
    $('i.fa-cart-plus').on('click', function () {
        var productId = $(this).attr('data-productId');
        var productName = $(this).attr('data-productName');

        console.log('here');
        $('#question').html('Add ' + productName + ' to cart?');
        $('#addLink').attr('href', '/product/putProductInCart?productId=' + productId);
        $('#myModal').modal('show');

        return false;
    });

    $('#addLink').click(function(){
        var amount = $('#amount').val();
        var link = $(this).attr('href')+'&amount=' + amount;

        $('#addLink').attr('href', link);
    });

});