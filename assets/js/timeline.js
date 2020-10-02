import 'anychart';
const $ = require('jquery');

$(document).ready(function() {
    // If the current page is the career page
    if (window.location.pathname.match(/carrer$/i)) {
        // Get the career data  
        var range_data = JSON.parse($('#json_range_data')[0].value);
        var moment_data = JSON.parse($('#json_moment_data')[0].value);

        // Create a chart
        var chart = anychart.timeline();

        // Range
        var range = chart.range(range_data);
        range.normal().fill("#1ABC9C");
        range.normal().stroke("#ABB2B9");

        // Moment
        var moment = chart.moment(moment_data);

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
