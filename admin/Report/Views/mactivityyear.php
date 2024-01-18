<div class="row invisible" data-toggle="appear">
    <div class="col">
        <!-- Card -->
        <div class="block">
            <!-- Card image -->
            <div class="block-header block-header-default">
                <h5 class="mb-0">Last 3 years activities</h5>
            </div>
            <!-- Card image -->
            <!-- Card content -->
            <div class="block-content block-content-full text-center">
                <canvas id="barChart" height="200px"></canvas>
            </div>
            <!-- Card content -->
        </div>
        <!-- Card -->
    </div>
</div>

<script type="text/javascript">
var barChartData = {
    labels: [
        "Households",
        "Agriculture",
        "Horticulture",
        "Livestock",
        "Fisheries",
        "Protective Irrg"
    ],
    datasets: [
    {
        <?= $current_year_count; ?>
    },
    {
        <?= $prev_year_count; ?>
    },
    {
        <?= $prev_prev_year_count; ?>
    }
    ]
    };

    var chartOptions = {
        responsive: true,
        legend: {
            position: "top",
        },
        //title: {
        //   display: true,
        //   text: "Chart.js Bar Chart"
        //},
        scales: {
            yAxes: [
                {
                    ticks: {
                    beginAtZero: true
                }
            }]
        },

    }
    var ctxB = document.getElementById("barChart").getContext('2d');
    var myBarChart = new Chart(ctxB, {
        type: 'bar',
        data: barChartData,
        options: chartOptions
    });
</script>



                