$(function () {
    //モーダル開く
    $('.review__show').on('click', function () {
        $('.review__modal').show();
    });

    //星マークチェック
    const $stars = $('.star');
    const $ratingInput = $('#rating-input');

    $stars.on('click', function () {
        const rating = $(this).data('value'); 
        $ratingInput.val(rating);

        $stars.removeClass('checked');

        $stars.each(function (index) {
            if (index < rating) {
                $(this).addClass('checked');
            }
        });
    });
});