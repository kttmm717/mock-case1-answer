$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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

    //メッセージのスクロール位置調整
    $(window).on('load', function () {
        const $messageBox = $('.message');
        setTimeout(function () {
            $messageBox.scrollTop($messageBox[0].scrollHeight);
        }, 0);
    });

    //メッセージ編集機能
    $('.edit__toggle').on('click', function () {
        const messageBox = $(this).closest('.myself');
        messageBox.find('.message__text').hide();
        messageBox.find('.edit__form').show();
        $(this).hide();
    });

    //メッセージ入力内容保持
    const $input = $('.send__message');

    const saveMessage = localStorage.getItem('message');
    if (saveMessage) {
        $input.val(saveMessage);
    }
    $input.on('input', function () {
        localStorage.setItem('message', $(this).val()); 
    });

    $('.send__btn').on('click', function (e) {
        e.preventDefault();
        localStorage.removeItem('message');
        $('.send__form').submit();
    });

    //ページ読み込み時に既読
    $('.partner').each(function () {
        const messageId = $(this).data('message-id');
        
        $.ajax({
            url: "/messages/read/" + messageId,
            method: 'post',
            success: function () {
                console.log(`メッセージ${messageId}を既読にしました`);
            },
            error: function () {
                console.log(`メッセージ${messageId}が既読に失敗しました`);
            }
        });
    });
});