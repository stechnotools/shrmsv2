<div class="row invisible" data-toggle="appear">
    <div class="col">
        <!-- Card -->
        <div class="block">
            <!-- Card image -->
            <div class="block-header block-header-default">
                <h5 class="mb-0">Villages data in each Cluster</h5>
            </div>
            <!-- Card image -->
            <!-- Card content -->
            <div class="block-content block-content-full text-center">
                <canvas id="doughnutChart" height="200px"></canvas>
            </div>
            <!-- Card content -->
        </div>
        <!-- Card -->
    </div>
</div>

<script type="text/javascript">
    var ctxD = document.getElementById("doughnutChart").getContext('2d');
    var doughnutChart = new Chart(ctxD, {
        type: 'doughnut',
        data: {
            labels: ["CLUSTER1", "CLUSTER2", "CLUSTER3", "CLUSTER4"],
            datasets: [{
                data: [<?= sizeof($cluster1_count['message']) ?>, <?= sizeof($cluster2_count['message']) ?>, <?= sizeof($cluster3_count['message']) ?>, <?= sizeof($cluster4_count['message']) ?>],
                backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1"],
                hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5"]
            }]
        },
        options: {
            responsive: true,
            onClick: function (e) {
                var activePoints = doughnutChart.getElementsAtEvent(e);
                var selectedIndex = activePoints[0]._index;
                console.log(this.data.labels[selectedIndex]);
                var cluster = this.data.labels[selectedIndex];
                $.ajax({
                    method: "POST",
                    async: false,
                    url: "<?= base_url(); ?>api/getcluster",
                    data: {cluster: cluster}
                }).done(function (msg) {
                    msg = msg.replace("<\/td>", "");
                    msg = msg.replace("<\/tr>", "");
                    msg = msg.replace("<\/table>", "");
                    $('#ClusterdataModal').modal('hide');
                    $('#ClusterData').html(msg);
                    $('#ClusterTitle').html(cluster);
                    $('#ClusterdataModal').modal('show');
                });
            }
            //onClick: graphClickEvent
        }
    });
</script>



                