$(function() {
	var barOptions = {
		series: {
			bars: {
				show: true,
				barWidth: 0.6,
				fill: true,
				fillColor: {
					colors: [{
						opacity: 0.8
					}, {
						opacity: 0.8
					}]
				}
			}
		},
		xaxis: {
			tickDecimals: 0
		},
		colors: ["#1ab394"],
		grid: {
			color: "#999999",
			hoverable: true,
			clickable: true,
			tickColor: "#D4D4D4",
			borderWidth:0
		},
		legend: {
			show: false
		},
		tooltip: true,
		tooltipOpts: {
			content: "%x月: logged in %y times"
		}
	};
	var barData = {
		label: "bar",
		data: logdata
	};
	$.plot($("#flot-bar-chart"), [barData], barOptions);

});
function viewAll(user_id){
	location.href="/endusers/"+user_id+'?month=';
}
function viewMonth(user_id, month){
	location.href="/endusers/"+user_id+'?month='+month;
}