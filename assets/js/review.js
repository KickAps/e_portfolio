// TODO : retirer bootstrap/jquery
const $ = require("jquery");
document.addEventListener("DOMContentLoaded", function() {
    // If the current page is the profile page
    if(window.location.pathname.match(/review$/i)) {
        const stars_group = document.querySelectorAll('.stars');
        // Init stars with inputs values (case of refresh or return)
        checkStars(stars_group, 0, document.querySelector("#review_markOne").value - 1);
        checkStars(stars_group, 1, document.querySelector("#review_markTwo").value - 1);
        checkStars(stars_group, 2, document.querySelector("#review_markThree").value - 1);

        // Click event
        for(let i = 0; i < stars_group.length; i++) {
            const stars = stars_group[i].children;
            for(let j = 0; j < stars.length; j++) {
                stars[j].addEventListener("click", function(e) {
                    checkStars(stars_group, i, j);
                    updateInput(i, j);
                });
            }
        }
    }
});

function checkStars(container, x, y) {
    resetStars(container, x);
    for(let i = 0; i < container[x].children.length; i++) {
        if(i > y) {
            continue;
        }
        let star = container[x].children[i];
        star.classList.remove("far");
        star.classList.add("fas");

    }
}

function resetStars(container, x) {
    for(let i = 0; i < container[x].children.length; i++) {
        let star = container[x].children[i];
        star.classList.remove("fas");
        star.classList.add("far");
    }
}

function updateInput(x, y) {
    let selector = "";
    switch(x) {
        case 0:
            selector = "#review_markOne";
            break;
        case 1:
            selector = "#review_markTwo";
            break;
        case 2:
            selector = "#review_markThree";
            break;
    }
    document.querySelector(selector).setAttribute('value', y + 1);
}

window.handleReviewLink = function() {
    let review_link_input = $('input#review_link');
    review_link_input.on('click', function(e) {
        e.preventDefault();
        review_link_input.select();
        navigator.clipboard.writeText($(this).attr('value')).then();
    });

    // Show tooltip
    review_link_input.tooltip({
        animated: 'fade',
        placement: 'bottom',
        trigger: 'click'
    });

    // Hide tooltip
    review_link_input.mouseout(function() {
        review_link_input.tooltip('hide');
    });

    $('#review_link_modal').on('shown.bs.modal', function(e) {
        review_link_input.attr('value', $(e.relatedTarget).data('href'));
    });

    $('#review_link_modal').on('hidden.bs.modal', function() {
        review_link_input.attr('value', "");
    });
}

function showReviews(url) {
    let request = new XMLHttpRequest();
    request.open('GET', url, true);

    request.onload = function() {
        if(this.status >= 200 && this.status < 400) {
            document.querySelector("#reviews_list_modal_body").insertAdjacentHTML("beforeend", this.response);
        } else {
            // We reached our target server, but it returned an error

        }
    };

    request.onerror = function() {
        // There was a connection error of some sort
    };

    request.send();
}

window.handleReviewsListModal = function() {
    $('#reviews_list_modal').on('shown.bs.modal', function(e) {
        document.querySelector("#reviews_list_modal_title").innerHTML = $(e.relatedTarget).data('title');
        showReviews($(e.relatedTarget).data('href'));
    });
    $('#reviews_list_modal').on('hidden.bs.modal', function() {
        document.querySelector("#reviews_list_modal_title").innerHTML = "";
        document.querySelector("#reviews_list_modal_body").innerHTML = "";
    });
}