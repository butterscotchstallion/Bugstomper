

$(document).ready(function() {
	// Status distribution
	$.getJSON('/pretty-graphs-and-stuff/issue-distribution', function(data) {
		$.plot($("#statusChart"), data,
		{
			series: {
				pie: { 
					show: true,
					radius: 1,
					label: {
						show: true,
						radius: 2/3,
						formatter: function(label, series){
							return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">'+label+'<br/>'+Math.round(series.percent)+'%</div>';
						},
						threshold: 0.1
					}
				}
			},
			legend: {
				show: false
			}
		});
	});
	
	// Assigned distribution
	$.getJSON('/pretty-graphs-and-stuff/assignee-distribution', function(data) {
		$.plot($("#assignedChart"), data,
		{
			series: {
				pie: { 
					show: true,
					radius: 1,
					label: {
						show: true,
						radius: 2/3,
						formatter: function(label, series){
							return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">'+label+'<br/>'+Math.round(series.percent)+'%</div>';
						},
						threshold: 0.1
					}
				}
			},
			legend: {
				show: false
			}
		});
	});
	
	// Opener distribution
	$.getJSON('/pretty-graphs-and-stuff/opener-distribution', function(data) {
		$.plot($("#openerChart"), data,
		{
			series: {
				pie: { 
					show: true,
					radius: 1,
					label: {
						show: true,
						radius: 2/3,
						formatter: function(label, series){
							return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">'+label+'<br/>'+Math.round(series.percent)+'%</div>';
						},
						threshold: 0.1
					}
				}
			},
			legend: {
				show: false
			}
		});
	});
});