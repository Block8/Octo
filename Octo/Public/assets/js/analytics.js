$(document).ready(function(e) {
    renderChart($('#pageviews-chart'), 'pageviews');
    renderTable($('#pageviews-list'), 'top-pages');
    $('#pageviews').show();

    renderChart($('#uniquePageviews-chart'), 'uniquePageviews');
    renderTable($('#uniquePageviews-list'), 'top-unique-pages');

    renderChart($('#visitors-chart'), 'visitors');
    renderResponsivePie($('#responsive-chart'));

    if($('ul.nav-tabs#analytics').length > 0) {
        $('ul.nav-tabs#analytics li a').on('click', function(e) {
            e.preventDefault();
            var id = $(this).attr('href').replace('#', '');
            $('#analytics_container div.widget-body').hide();
            $('#' + id).fadeIn('slow');
        });
    }
});

function renderTable(div, metric) {
    var colors = ['greenDark', 'magenta', 'blue', 'orange', 'pink'];

    $.ajax({
        url: "http://businesslincolnshire.dev.resystems.net/backoffice/analytics/" + metric,
    }).done(function(data) {
        console.log(data);
        data = JSON.parse(data);

        for(var i in data) {
            var item = data[i];
            var row = '<div class="col-xs-6 col-sm-6 col-md-12 col-lg-12">' +
                '<span class="text">' + item.name + '<span class="pull-right">' + item.metric + ' views</span> </span>' +
                '<div class="progress">' +
                '<div class="progress-bar bg-color-' + colors[i] + '" style="width: ' + item.percentage + '%;"></div>' +
                '</div></div>';

            div.append(row);
        }
    });
}

function renderChart(chart, metric) {
    if(chart.html().length > 50) {
        return true;
    }

    var data = [], totalPoints = 30, $UpdatingChartColors = chart.css('color');

    $.ajax({
        url: "http://businesslincolnshire.dev.resystems.net/backoffice/analytics/metric/" + metric,
    }).done(function(data) {
        data = JSON.parse(data);
        var meta = data.shift();

        var ticks = [];
        for (var tick in meta.ticks) {
            ticks.push([tick, meta.ticks[tick]]);
        }

        var points = [];
        for (var i = 0; i < data.length; i++) {
            points.push([i, data[i].value]);
        }

        // Setup options
        var options = {
            yaxis : {
                min : 0,
                max : meta.max
            },
            xaxis : {
                min : 0,
                max : 30,
                ticks: ticks
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

        var plot = $.plot(chart, [points], options);
    });
}

function renderResponsivePie(chart) {
    var points = [];
    var colors = ['#496949', '#6E3671', '#57889C'];

    $.ajax({
        url: "http://businesslincolnshire.dev.resystems.net/backoffice/analytics/responsive",
    }).done(function(data) {
        data = JSON.parse(data);
        for(var i = 0; i < data.length; i++) {
            var point = { data: data[i].count, color: colors[i] };
            points.push(point);

            $('#responsive-' + i).text(data[i].percentage);

            $.plot(chart, points, {
                series: {
                    pie: {
                        show: true
                    }
                }
            });
        }
    });
}
