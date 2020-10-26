const $ = require('jquery');

$(document).ready(function()
{
    // If the current page is the profile page
    if (window.location.pathname.match(/\/user$/i)) {
        var copyLinkInput = $('input[name ="personnal-link-input"]');

        // Set input width based on characters count
        var width = copyLinkInput[0].value.length;
        copyLinkInput[0].style.width = width*9 + "px";

        // Change cursor
        copyLinkInput.mouseover(function(e){
            copyLinkInput[0].style.cursor = "pointer";
        });

        // Copy to clipboard
        copyLinkInput.on("click", function(e){
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
        copyLinkInput.mouseout(function(e){
            copyLinkInput.tooltip('hide');
        });
    }
});