const $ = require('jquery');
import Cropper from 'cropperjs';

$(document).ready(function() {
    var copyLinkInput = $('input[name ="personnal-link-input"]');
    // If the current page is the profile page
    if(copyLinkInput.length !== 0) {
        // Change cursor
        copyLinkInput.mouseover(function(e) {
            copyLinkInput[0].style.cursor = "pointer";
        });

        // Copy to clipboard
        copyLinkInput.on("click", function(e) {
            copyLinkInput[0].select();
            copyLinkInput[0].setSelectionRange(0, 99999)

            document.execCommand("copy");
        });

        // Show tooltip
        copyLinkInput.tooltip({
            animated: 'fade',
            placement: 'bottom',
            trigger: 'click'
        });

        // Hide tooltip
        copyLinkInput.mouseout(function(e) {
            copyLinkInput.tooltip('hide');
        });
    }


    // If the current page is the profile update page
    if(window.location.pathname.match(/user\/update$/i)) {

        $('.avatar-update').mouseover(function(e) {
            $(e.currentTarget).find('.btn').css("display", "block");
        });

        $('.avatar-update').mouseout(function(e) {
            $(e.currentTarget).find('.btn').css("display", "none");
        });

        var canvas = $('#cropCanvas')[0];
        var originalAvatar = $('#avatar');

        // Cropper init
        var cropper = new Cropper(canvas, {
            aspectRatio: 1 / 1,
            movable: false,
            minContainerWidth: 250,
            minContainerHeight: 250,
            preview: '.preview'
        });

        $('#cropModal').on('shown.bs.modal', function() {
            cropper.replace(originalAvatar.attr('src'));
        });

        $("input[name='img']").on("change", function() {
            var file = $(this)[0].files[0];
            // Replace the cropper src image
            cropper.replace(window.URL.createObjectURL(file));
        });

        $("#crop").click(function() {
            cropper.getCroppedCanvas().toBlob((blob) => {
                // Form
                const formData = new FormData();
                formData.append('croppedImage', blob);

                // Ajax request
                $.ajax('/avatar/upload', {
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        document.location.reload(true);
                    },
                    error: function(response) {
                        console.log(response['responseText']);
                    }
                });
            }, 'image/jpeg');
        });

        $('#cropModal').on('hidden.bs.modal', function() {
            cropper.destroy();
            // Cropper init
            cropper = new Cropper(canvas, {
                preview: '.preview',
                aspectRatio: 1 / 1,
                movable: false,
                minContainerWidth: 250,
                minContainerHeight: 250
            });
        })
    }
});
