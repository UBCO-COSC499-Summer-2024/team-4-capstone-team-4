@vite(['resources/css/visualizations.css'])

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawGraphs);

    const chartConfigs = [
        {
            elementId: 'donutchart',
            chartType: 'PieChart',
            data: [
                ['Task', 'Hours per Day'],
                ['Work', 11],
                ['Eat', 2],
                ['Commute', 2],
                ['Watch TV', 2],
                ['Sleep', 7]
            ],
            options: {
                title: 'My Daily Activities',
                pieHole: 0.4,
            }
        },
        {
            elementId: 'curve_chart',
            chartType: 'LineChart',
            data: [
                ['Month', 'Sales', 'Expenses'],
                ['January',  1000,  400],
                ['February', 1170,  460],
                ['March',    660, 1120],
                ['April',   1030,  540]
            ],
            options: {
                title: 'Hours',
                legend: { position: 'bottom' }
            }
        },
        {
            elementId: 'bubble_chart',
            chartType: 'BubbleChart',
            data: [
                ['ID', 'X', 'Y', 'Temperature'],
                ['',   80,  167,  120],
                ['',   79,  136,  130],
                ['',   78,  184,  50],
                ['',   72,  278,  230],
                ['',   81,  200,  210],
                ['',   72,  170,  100],
                ['',   68,  477,  80]
            ],
            options: {
                colorAxis: { colors: ['yellow', 'red'] }
            }
        }
    ];

    function drawGraphs() {
        chartConfigs.forEach(config => {
            drawGraph(config);
        });
    }

    function drawGraph(config) {
        const data = google.visualization.arrayToDataTable(config.data);
        const chart = new google.visualization[config.chartType](document.getElementById(config.elementId));
        chart.draw(data, config.options);
    }
</script>
<section class="dash-graph-preview">
    <div id="curve_chart" class="graph-preivew-item"></div>
    <div id="donutchart" class="graph-preivew-item"></div>
    <div id="bubble_chart" class="graph-preview-item"></div>
</section>