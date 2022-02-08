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