

$(document).ready(function() {
    // Comment distribution
	$.getJSON('/pretty-graphs-and-stuff/comment-distribution', function(data) {
		$.plot($("#commentChart"), data,
		{
			series: {
				pie: { 
					show: true,
					radius: 1,
					label: {
						show: true,
						radius: 2/3,
						formatter: function(label, series){
							return '<div style="font-size:1em;text-align:center;padding:2px;color:#000;"><b>'+label+'</b><br/>'+Math.round(series.percent)+'%</div>';
						},
						threshold: 0.1
					}
				}
			},
			legend: {
				show: true
			}
		});
	});
    
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
							return '<div style="font-size:1em;text-align:center;padding:2px;color:#000;"><b>'+label+'</b><br/>'+Math.round(series.percent)+'%</div>';
						},
						threshold: 0.1
					}
				}
			},
			legend: {
				show: true
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
							return '<div style="font-size:1em;text-align:center;padding:2px;color:#000;"><b>'+label+'</b><br/>'+Math.round(series.percent)+'%</div>';
						},
						threshold: 0.1
					}
				}
			},
			legend: {
				show: true
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
							return '<div style="font-size:1em;text-align:center;padding:2px;color:#000;"><b>'+label+'</b><br/>'+Math.round(series.percent)+'%</div>';
						},
						threshold: 0.1
					}
				}
			},
			legend: {
				show: true
			}
		});
	});
});