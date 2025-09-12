$(function() {
    "use strict";

		  // chart1
		  var ctx = document.getElementById('chart1').getContext('2d');
		  var myChart = new Chart(ctx, {
			  type: 'line',
			  data: {
				  labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct"],
				  datasets: [{
					  label: 'Visitor',
					  data: [3, 3, 8, 5, 7, 4, 6, 4, 6, 3],
					  backgroundColor: [
						  '#5e72e4'
					  ],
					 fill: {
						  target: 'origin',
						  above: 'rgb(94 114 228 / 14%)',   // Area will be red above the origin
						  below: 'rgb(94 114 228 / 14%)'   // And blue below the origin
						}, 
					  tension: 0.4,
					  borderColor: [
						  '#5e72e4'
					  ],
					  pointRadius :"0",
					  borderWidth: 3
				  }
				  ]
			  },
			  options: {
				  maintainAspectRatio: false,
				  plugins: {
					  legend: {
						position: 'bottom',
						  display: true,
					  }
				  },
				  scales: {
					  y: {
						  beginAtZero: true
					  }
				  }
			  }
		  });
		
	

  // chart2
  var ctx = document.getElementById('chart2').getContext('2d');
  var myChart = new Chart(ctx, {
	  type: 'bar',
	  data: {
		  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
		  datasets: [{
			  label: 'Direct',
			  data: [10, 15, 20, 25, 20, 15, 10],
			  backgroundColor: [
				  '#fb6340'
			  ],
			  borderColor: [
				  '#fb6340'
			  ],
			  borderWidth: 0
		  },
		  {
			  label: 'Affiliate',
			  data: [10, 15, 20, 25, 20, 15, 10],
			  backgroundColor: [
				  'rgb(251 99 64 / 18%)'
			  ],
			  borderColor: [
				  'rgb(251 99 64 / 18%)'
			  ],
			  borderWidth: 0
		  }]
	  },
	  options: {
		  maintainAspectRatio: false,
		  barPercentage: 0.35,
		  //categoryPercentage: 0.5,
		  plugins: {
			  legend: {
				position:'bottom',
				display: true,
			  }
		  },
		  scales: {
			  x: {
				stacked: true,
				beginAtZero: true
			  },
			  y: {
				stacked: true,
				beginAtZero: true
			  }
			}
	  }
  });


		

    // chart 5
	var ctx = document.getElementById("chart5").getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: ['1', '2', '3', '4', '5', '6', '7', '7', '7', '7', '7', '7'],
			datasets: [{
				label: 'Total Earning',
				data: [39, 19, 25, 16, 31, 39, 23, 20, 23, 18, 15, 20],
				backgroundColor: [
					'#2dce89'
				],
				borderColor: [
					'#2dce89'
				],
				borderWidth: 0,
				borderRadius: 20
			},
			{
				label: 'Total Sales',
				data: [27, 12, 26, 15, 21, 27, 13, 19, 32, 22, 18, 30],
				backgroundColor: [
					'rgb(45 206 137 / 25%)'
				],
				borderColor: [
					'rgb(45 206 137 / 25%)'
				],
				borderWidth: 0,
				borderRadius: 20
			}]
		},
		options: {
			maintainAspectRatio: false,
			barPercentage: 0.7,
			categoryPercentage: 0.45,
			plugins: {
				legend: {
					maxWidth: 20,
					boxHeight: 20,
					position: 'bottom',
					display: false,
				}
			},
			scales: {
				x: {
					stacked: true,
					beginAtZero: true,
					display: false,
				},
				y: {
					stacked: true,
					beginAtZero: true,
					display: false,
				}
			}
		}


	});
	
	
		
		
   });	 
   