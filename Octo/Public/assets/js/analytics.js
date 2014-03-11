$(document).ready(function(e) {
    var data = [], totalPoints = 200, $UpdatingChartColors = $("#pageviews-chart").css('color');

    function getRandomData() {
        if (data.length > 0)
            data = data.slice(1);

        // do a random walk
        while (data.length < totalPoints) {
            var prev = data.length > 0 ? data[data.length - 1] : 50;
            var y = prev + Math.random() * 10 - 5;
            if (y < 0)
                y = 0;
            if (y > 100)
                y = 100;
            data.push(y);
        }

        // zip the generated y values with the x values
        var res = [];
        for (var i = 0; i < data.length; ++i)
            res.push([i, data[i]])
        return res;
    }

    // setup plot
    var options = {
        yaxis : {
            min : 0,
            max : 100
        },
        xaxis : {
            min : 0,
            max : 100
        },
        colors : [$UpdatingChartColors],
        series : {
            lines : {
                lineWidth : 1,
                fill : true,
                fillColor : {
                    colors : [{
                        opacity : 0.4
                    }, {
                        opacity : 0
                    }]
                },
                steps : false

            }
        }
    };

    var plot = $.plot($("#pageviews-chart"), [getRandomData()], options);
});
