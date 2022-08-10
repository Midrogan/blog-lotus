$(document).ready(function () {
    let like = $(".button_like").hasClass("active"), likeCount = $(".articles_like").html();
    like ? $('.button_like').attr("src", "/media/img/like_active.png") : $('.button_like').attr("src", "/media/img/like_inactive.png");

    let bookmark = $(".button_bookmark").hasClass("active"), bookmarkCount = $(".articles_bookmark").html();
    bookmark ? $('.button_bookmark').attr("src", "/media/img/bookmark_active.png") : $('.button_bookmark').attr("src", "/media/img/bookmark_inactive.png");

    $('.button_like').click(function () {

        var params = {
            'id': $(this).attr('data-id')
        };
        $.post('/article/like', params, function (data) {
            console.log(data);
        });
        likeCount = like ? --likeCount : ++likeCount;
        like = !like;
        $('.articles_like').html(likeCount);
        like ? $('.button_like').attr("src", "/media/img/like_active.png") : $('.button_like').attr("src", "/media/img/like_inactive.png");

        return false;
    });

    $('.button_bookmark').click(function () {

        var params = {
            'id': $(this).attr('data-id')
        };
        $.post('/article/bookmark', params, function (data) {
            console.log(data);
        });
        bookmarkCount = bookmark ? --bookmarkCount : ++bookmarkCount;
        bookmark = !bookmark;
        $('.articles_bookmark').html(bookmarkCount);
        bookmark ? $('.button_bookmark').attr("src", "/media/img/bookmark_active.png") : $('.button_bookmark').attr("src", "/media/img/bookmark_inactive.png");

        return false;
    });
});

