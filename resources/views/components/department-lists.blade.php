@vite(['resources/css/department-lists.css'])

<div class="department-list">
    <div class="department-preview glass">
        <x-chart :chart="$chart4"/>
    </div>
    
    <div class="department-preview glass">
        <x-chart :chart="$chart2"/>
    </div>
    
    <div class="department-preview glass">
        <x-chart :chart="$chart3"/>
    </div>
</div>