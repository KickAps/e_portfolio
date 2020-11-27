const $ = require('jquery');

function initSelectMainImage() {
    var tmp = [];
    var path = "";
    var name = "";
    var uniqueName = "";
    var option = null;
    var images = $('.project-image-update');

    // Get the current main image
    var mainImage = $('#main-image')[0].value;

    images.each(function(i){
        // Get the unique name of each image
        path = $( this ).find('img')[0].src;
        tmp = path.split('/');
        uniqueName = tmp[tmp.length - 1];

        // Get the name of each image
        name = $( this ).find('img')[0].name;

        if (uniqueName === mainImage) {
            // Add the selected option by default to the select
            option = new Option(name, uniqueName, true, true);
        } else {
            // Add the option to the select
            option = new Option(name, uniqueName);
        }
        $('#project_mainImage').append($(option));
    });

    if (images.length !== 0) {
        $('#project_mainImage')[0].disabled = false;
    }

}

function imagesManager(action = null) {
    $('#project_mainImage')[0].disabled = true;

    // Get the images limit
    var imagesLimit = $('#images-limit')[0].value;
    var imagesCount = $('#images-count')[0].value;

    // Set the images current count
    $('label[for="project_images"]')[0].innerHTML = $('label[for="project_images"]')[0].innerHTML.replace("$c", imagesCount);

    // Set the helper
    $('#project_images_help')[0].innerHTML = $('#project_images_help')[0].innerHTML.replace("$l", imagesLimit);

    if (imagesLimit - imagesCount < 1) {
        $('.custom-file-input')[0].disabled = true;
    }

    $('.custom-file-input').on('change', function(e) {
        var images = e.currentTarget.files;
        var imagesName = [];
        var option = null;

        $('#project_mainImage')[0].disabled = false;
        $('#project_mainImage').empty();
        $('.custom-file-label').html('');

        if (action === "update") {
            initSelectMainImage();
        }

        for (var i = 0; i < images.length; i++) {
            name = images[i].name;
            imagesName.push(name);

            // Add the option to the select
            option = new Option(name, name);
            $('#project_mainImage').append($(option));
        }

        if (images.length === 0 && action !== "update") {
            $('#project_mainImage')[0].disabled = true;
        }

        $('.custom-file-label').html(imagesName.join(' / '));
    });

    $('.project-image-update').mouseover(function(e){
        $(e.currentTarget).find('.btn').css("display", "block");
    });

    $('.project-image-update').mouseout(function(e){
        $(e.currentTarget).find('.btn').css("display", "none");
    });

    // Hide the day selector
    $('#project_createdAt_day').css("display", "none");
}

$(document).ready(function()
{
    // If the current page is the project updating page (ex: project/42/update)
    if (window.location.pathname.match(/project\/[0-9]*\/update/i)) {
        imagesManager("update");
        initSelectMainImage();
    // If the current page is the project creating page
    } else if (window.location.pathname.match(/project\/create/i)) {
        imagesManager();
    }
});
