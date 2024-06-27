@props(['chart'])

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/ProgressBarPlugin.js"></script> 

<div style="width:60%;">
    {!! $chart->render() !!}
</div>



