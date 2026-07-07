<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.flot.js');
Yii::app()->clientScript->registerScript('chart', '
    $.plot($("#placeholder"), [' . $chartData . '], {
		bars: {
			show: true,
			align: "center",
			barWidth: 0.5,
		},
        grid: { hoverable: true },
        xaxis: ' . $chartAxisX . ',
        yaxis: ' . $chartAxisY . '
   });
   $("#placeholder").bind("plothover", function (event, pos, item) {
        $("#tooltip").remove();
        if (item) {
            var tooltip = item.series.data[item.dataIndex][2];
            
            $("<div id=\"tooltip\">" + tooltip + "</div>")
            .css({
                position: "absolute",
                top: item.pageY - 50,
                left: item.pageX - 50,
                border: "1px solid #BBBBFF",
                padding: "3px",
                "background-color": "#EEEEFF",
            }).appendTo("body").show();
        }
    });
	
	$.plot($("#placeholder_sale"), [' . $chartSaleData . '], {
		bars: {
			show: true,
			align: "center",
			barWidth: 0.5,
		},
        grid: { hoverable: true },
        xaxis: ' . $chartSaleAxisX . ',
        yaxis: ' . $chartSaleAxisY . '
   });
   $("#placeholder_sale").bind("plothover", function (event, pos, item) {
        $("#tooltip").remove();
        if (item) {
            var tooltip = item.series.data[item.dataIndex][2];
            
            $("<div id=\"tooltip\">" + tooltip + "</div>")
            .css({
                position: "absolute",
                top: item.pageY - 50,
                left: item.pageX - 50,
                border: "1px solid #BBBBFF",
                padding: "3px",
                "background-color": "#EEEEFF",
            }).appendTo("body").show();
        }
    });
');
?>

<div>
	<table>
		<tr>
			<td style="text-align: center">
				<div style="width: 800px; font-size: 24px; color: black">Grafik Pembelian</div>
				<div id="placeholder" style="width: 800px; height: 200px"></div>
			</td>
		</tr>
	</table>
</div>

<div>
	<table>
		<tr>
			<td style="text-align: center">
				<div style="width: 800px; font-size: 24px; color: black">Grafik Penjualan</div>
				<div id="placeholder_sale" style="width: 800px; height: 200px"></div>
			</td>
		</tr>
	</table>
</div>
