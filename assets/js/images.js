import $ from 'jquery';

$('.custom-file-input').on('change', function(e){
    var images = e.currentTarget.files;
    var imagesName = [];

    for (var i = images.length - 1; i >= 0; i--) {
        imagesName.push(images[i].name)
    }

    $('.custom-file-label').html(imagesName.join(' - '));
});

$('.project-image').mouseover(function(e){
    $(e.currentTarget).find('.btn').css("display", "block");
});

$('.project-image').mouseout(function(e){
    $(e.currentTarget).find('.btn').css("display", "none");
});
