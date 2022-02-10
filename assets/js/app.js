/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../scss/app.scss';

import '../favicon/favicon.ico';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';
import 'bootstrap';

// Disable the browser back button feature when logout
noReturnOnLogout();

function noReturnOnLogout() {
    if(window.location.href.match(/\/login$/i)) {
        drownHistory();
        window.location = window.location.href + "?logout";
    }

    if(window.location.href.match(/\/login\?logout$/i)) {
        drownHistory();
    }
}

function drownHistory() {
    for(var i = 0; i < 15; i++) {
        window.history.pushState(null, "", window.location.href);
    }
    window.onpopstate = function() {
        document.location.reload(true);
        window.history.pushState(null, "", window.location.href);
    };
}