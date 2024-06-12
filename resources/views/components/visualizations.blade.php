{{-- temp --}}

{{-- <div class="mx-2">
    <h2 class="font-bold text-xl">Performance Preview</h2>
    <div class="flex">
        <div class="p-20 text-2xl bg-white">Chart</div>
        <div class="p-20 text-2xl bg-white mx-4">Chart</div>
        <div class="p-20 text-2xl bg-white">Chart</div>
    </div>
</div> --}}

<!DOCTYPE html>
<html>
    <head>
      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
  
        function drawChart() {
          var data = google.visualization.arrayToDataTable([
            ['Month', 'Sales', 'Expenses'],
            ['January',  1000,      400],
            ['February',  1170,      460],
            ['March',  660,       1120],
            ['April',  1030,      540]
          ]);
  
          var options = {
            title: 'Hours',
            legend: { position: 'bottom' }
          };
  
          var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
  
          chart.draw(data, options);
        }
      </script>
    </head>
    <body>
      <div id="curve_chart" style="width: 900px; height: 500px"></div>
    </body>
  </html>
  >

