<div class="row invisible" data-toggle="appear">
	<!-- Row #1 -->
	<div class="col-6 col-xl-3">
		<a class="block block-link-pop text-right bg-primary" href="javascript:void(0)">
			<div class="block-content block-content-full clearfix border-black-op-b border-3x">
				<div class="float-left mt-10 d-none d-sm-block">
					<i class="si si-bag fa-3x text-primary-light"></i>
				</div>
				<div class="font-size-h3 font-w600 text-white js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="<?=$household;?>"><?=$household;?></div>
				<div class="font-size-sm font-w600 text-uppercase text-white-op">Households</div>
			</div>
		</a>
	</div>
	<div class="col-6 col-xl-3">
		<a class="block block-link-pop text-right bg-earth" href="javascript:void(0)">
			<div class="block-content block-content-full clearfix border-black-op-b border-3x">
				<div class="float-left mt-10 d-none d-sm-block">
					<i class="si si-bag fa-3x text-earth-light"></i>
				</div>
				<div class="font-size-h3 font-w600 text-white js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="<?=$aggriculture;?>"><?=$aggriculture;?></div>
				<div class="font-size-sm font-w600 text-uppercase text-white-op">Aggriculture</div>
			</div>
		</a>
	</div>
	<div class="col-6 col-xl-3">
		<a class="block block-link-pop text-right bg-elegance" href="javascript:void(0)">
			<div class="block-content block-content-full clearfix border-black-op-b border-3x">
				<div class="float-left mt-10 d-none d-sm-block">
					<i class="si si-bag fa-3x text-elegance-light"></i>
				</div>
				<div class="font-size-h3 font-w600 text-white js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="<?=$horticulture;?>"><?=$horticulture;?></div>
				<div class="font-size-sm font-w600 text-uppercase text-white-op">Horticulture</div>
			</div>
		</a>
	</div>
	<div class="col-6 col-xl-3">
		<a class="block block-link-pop text-right bg-corporate" href="javascript:void(0)">
			<div class="block-content block-content-full clearfix border-black-op-b border-3x">
				<div class="float-left mt-10 d-none d-sm-block">
					<i class="si si-bag fa-3x text-corporate-light"></i>
				</div>
				<div class="font-size-h3 font-w600 text-white js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="<?=$horticulture;?>"><?=$horticulture;?></div>
				<div class="font-size-sm font-w600 text-uppercase text-white-op">Fisheries</div>
			</div>
		</a>
	</div>
	<div class="col-6 col-xl-3">
		<a class="block block-link-pop text-right bg-pulse" href="javascript:void(0)">
			<div class="block-content block-content-full clearfix border-black-op-b border-3x">
				<div class="float-left mt-10 d-none d-sm-block">
					<i class="si si-bag fa-3x text-pulse-light"></i>
				</div>
				<div class="font-size-h3 font-w600 text-white js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="<?=$horticulture;?>"><?=$horticulture;?></div>
				<div class="font-size-sm font-w600 text-uppercase text-white-op">Livestock</div>
			</div>
		</a>
	</div>
    <div class="col-6 col-xl-3">
		<a class="block block-link-pop text-right bg-flat" href="javascript:void(0)">
			<div class="block-content block-content-full clearfix border-black-op-b border-3x">
				<div class="float-left mt-10 d-none d-sm-block">
					<i class="si si-bag fa-3x text-flat-light"></i>
				</div>
				<div class="font-size-h3 font-w600 text-white js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="<?=$horticulture;?>"><?=$horticulture;?></div>
				<div class="font-size-sm font-w600 text-uppercase text-white-op">Institutions</div>
			</div>
		</a>
	</div>
    
	<!-- END Row #1 -->
</div>
<div class="row invisible d-none" data-toggle="appear">
	<!-- Row #2 -->
	<div class="col-md-6">
		<div class="block">
			<div class="block-header block-header-default">
				<h3 class="block-title">
					No of Farmer <small>This Year</small>
				</h3>
				
			</div>
			<div class="block-content">
				<div id="total_farmer"></div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="block">
			<div class="block-header block-header-default">
				<h3 class="block-title">
					Districtwise <small>Household</small>
				</h3>
				
			</div>
			<div class="block-content">
				<div id="household_chart"></div>
			</div>
		</div>
	</div>
	<!-- END Row #2 -->
</div>
<?php js_start(); ?>
<script src="<?=theme_url('assets/js/codebase.app.js');?>"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script type="text/javascript">
	Highcharts.chart('total_farmer', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: 'Total Farmer in 2021-2022'
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		accessibility: {
			point: {
				valueSuffix: '%'
			}
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>: {point.percentage:.1f} %'
				}
			}
		},
		series: [{
			name: 'Farmers',
			colorByPoint: true,
			data: [{
				name: 'Households',
				y: 61.41,
				sliced: true,
				selected: true
			}, {
				name: 'Aggriculture',
				y: 11.84
			}, {
				name: 'Horticulture',
				y: 10.85
			}, {
				name: 'Fisheries',
				y: 4.67
			}, {
				name: 'Livestock',
				y: 4.18
			}, {
				name: 'Institutions',
				y: 1.64
			}]
		}]
	});
	
	// Create the chart
	Highcharts.chart('household_chart', {
		chart: {
			type: 'column'
		},
		title: {
			text: 'Districtwise Household in 2021-2022'
		},
		
		xAxis: {
			type: 'District'
		},
		yAxis: {
			title: {
				text: 'Total Household'
			}

		},
		legend: {
			enabled: false
		},
		plotOptions: {
			series: {
				borderWidth: 0,
				dataLabels: {
					enabled: true,
					format: '{point.y:.1f}%'
				}
			}
		},

		tooltip: {
			headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
			pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
		},

		series: [
			{
				name: "Households",
				colorByPoint: true,
				data: [
					{
						name: "Angul",
						y: 62.74
					},
					{
						name: "Balangir",
						y: 10.57
					},
					{
						name: "Malkangiri",
						y: 7.23
					}
					
				]
			}
		],
	});
</script>
<?php js_end(); ?>


                