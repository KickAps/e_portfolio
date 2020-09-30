import $ from 'jquery';

$('.custom-file-input').on('change', function(e){
    var images = e.currentTarget.files;
    var imagesName = [];

    $('#project_mainImage')[0].disabled = false;

    for (var i = 0; i < images.length; i++) {
        name = images[i].name;
        imagesName.push(name);

        var option = new Option(name, name);
        $('#project_mainImage').append($(option));
    }

    $('.custom-file-label').html(imagesName.join(' - '));
});

$('.project-image-update').mouseover(function(e){
    $(e.currentTarget).find('.btn').css("display", "block");
});

$('.project-image-update').mouseout(function(e){
    $(e.currentTarget).find('.btn').css("display", "none");
});
