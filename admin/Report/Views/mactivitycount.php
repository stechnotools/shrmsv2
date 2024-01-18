<div class="row invisible" data-toggle="appear">
    <div class="col">
        <!-- Card -->
        <div class="block">
            <!-- Card image -->
            <div class="block-header block-header-default">
                <h5 class="mb-0">Last 6 months Activity count</h5>
            </div>
            <!-- Card image -->
            <!-- Card content -->
            <div class="block-content block-content-full text-center">
                <canvas id="lineChart" height="200px"></canvas>
            </div>
            <!-- Card content -->
        </div>
        <!-- Card -->
    </div>
</div>

<script type="text/javascript">
    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    var today = new Date();
    var d;
    var month;
    var months = [];
    for (var i = 5; i >= 0; i -= 1) {
        d = new Date(today.getFullYear(), today.getMonth() - i, 1);
        month = monthNames[d.getMonth()];
        months.push(month + "-" + d.getFullYear());
    }

    var ctxL = document.getElementById("lineChart").getContext('2d');
    var myLineChart = new Chart(ctxL, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: "Households",
                data: [<?= $hh_last_count; ?>],
                backgroundColor: [
                    'rgba(128, 128, 255, .2)',
                ],
                borderColor: [
                    'rgba(0, 0, 255, .7)',
                ],
                borderWidth: 2
            },
                {
                    label: "Agriculture",
                    data: [<?= $agri_last_count; ?>],
                    backgroundColor: [
                        'rgba(255, 128, 128, .2)',
                    ],
                    borderColor: [
                        'rgba(255, 0, 0, .7)',
                    ],
                    borderWidth: 2
                },
                {
                    label: "Horticulture",
                    data: [<?= $hort_last_count; ?>],
                    backgroundColor: [
                        'rgba(51, 255, 119, .2)',
                    ],
                    borderColor: [
                        'rgba(0, 153, 51, .7)',
                    ],
                    borderWidth: 2
                },
                {
                    label: "Livestock",
                    data: [<?= $live_last_count; ?>],
                    backgroundColor: [
                        'rgba(153, 153, 153, .2)',
                    ],
                    borderColor: [
                        'rgba(0, 0, 0, .7)',
                    ],
                    borderWidth: 2
                },
                {
                    label: "Fisheries",
                    data: [<?= $fish_last_count; ?>],
                    backgroundColor: [
                        'rgba(255, 128, 255, .2)',
                    ],
                    borderColor: [
                        'rgba(204, 0, 204, .7)',
                    ],
                    borderWidth: 2
                },
                {
                    label: "Protective Irrg",
                    data: [<?= $criirri_last_count; ?>],
                    backgroundColor: [
                        'rgba(255, 255, 77, .2)',
                    ],
                    borderColor: [
                        'rgba(204, 204, 0, .7)',
                    ],
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true
        }
    });
</script>



                