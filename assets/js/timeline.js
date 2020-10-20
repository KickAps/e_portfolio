import 'anychart';
const $ = require('jquery');

$(document).ready(function() {
    // If the current page is the career page
    if (window.location.pathname.match(/career$/i)) {
        var current;
        // Get the career data  
        var range_data = JSON.parse($('#json_range_data')[0].value);
        var moment_data = JSON.parse($('#json_moment_data')[0].value);
        var project_data = JSON.parse($('#json_project_data')[0].value);

        // Create a chart
        var chart = anychart.timeline();

        // RANGE
        var range = chart.range(range_data);
        range.normal().fill("#1ABC9C");
        range.normal().stroke("#ABB2B9");

        range.tooltip().format(function(){
            var start = new Date(this.start).toLocaleDateString();
            var end = new Date(this.end).toLocaleDateString();
            var elems = document.querySelectorAll( ":hover" );
            var elem = elems[elems.length-3];

            current = this.x;
            elem.style.cursor = "pointer";

            return start + " - " + end;
        });

        // Configure tooltips of range
        range.tooltip().title().enabled(false);
        range.tooltip().separator().enabled(false);

        range.listen("dblClick", function() {
            scrollToElement(current);
        });

        // MOMENT
        var moment = chart.moment(moment_data);
        moment.normal().stroke("#1ABC9C");
        moment.markers().type("circle");
        moment.normal().markers().fill("#1ABC9C");

        moment.labels().format("{%y}");

        moment.tooltip().format(function(){
            current = this.value;
            var elems = document.querySelectorAll( ":hover" );
            var elem = elems[elems.length-3];
            elem.style.cursor = "pointer";

            return new Date(this.x).toLocaleDateString();
        });

        // Configure tooltips of moment
        moment.tooltip().title().enabled(false);
        moment.tooltip().separator().enabled(false);

        moment.listen("dblClick", function() {
            scrollToElement(current);
        });

        // PROJECT
        var project = chart.moment(project_data);
        project.normal().stroke("#1ABC9C");
        project.normal().markers().fill("#1ABC9C");

        project.direction("down");
        project.labels().useHtml(true);
        project.labels().format(
            "<span>\
                <b>Projet</b>\
                <br>\
                {%y}\
            </span>"
        );

        project.tooltip().format(function(){
            // Get the project id
            current = project_data[this.index]["id"];
            var elems = document.querySelectorAll( ":hover" );
            var elem = elems[elems.length-3];
            elem.style.cursor = "pointer";

            return new Date(this.x).toLocaleDateString();
        });

        // Configure tooltips of project
        project.tooltip().title().enabled(false);
        project.tooltip().separator().enabled(false);

        project.listen("dblClick", function() {
            window.location = '/project/' + current;
        });

        // Set the container id
        chart.container("timeline");

        // Configure the axis
        chart.axis().fill("#2c3e50");
        chart.axis().stroke("#ABB2B9");
        chart.axis().ticks().stroke("#ABB2B9", 2);

        // Define the timeline start / end
        chart.scale().minimum(Date.UTC(2010));
        chart.scale().maximum(Date.UTC(2022));

        // Initiate drawing the chart  
        chart.draw();

        $('.anychart-credits')[0].hidden = true;
    }
});

function scrollToElement(element) {
    var id = element.replace(' ', '-').replace('\'', '');
    var pos = $('#'+id)[0].getBoundingClientRect();
    window.scrollTo(0, pos.top-100);

    $('#'+id)[0].animate (
        [{ border: '1px solid red' },{ border: '1px solid white' }],
        { duration: 2000 }
    );
}
