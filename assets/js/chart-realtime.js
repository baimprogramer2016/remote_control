$(function() {
    setInterval(getDataRealtimePower, 3000); 
    // getDataRealtimePower();
    function getDataRealtimePower()
    {
        sethourPower = document.getElementById('sethourPower').value;

        $.ajax({
            type : "GET",  //type of method
            url  : "getdatarealtime.php?sethourPower="+sethourPower,  //your page
            success: function(response){  
            result  = JSON.parse(response);
            console.log(result); 
            
                var options = {
                    scales: {
                    yAxes: [{
                        ticks: {
                        beginAtZero: false
                        },
                        gridLines: {
                        color: "rgba(204, 204, 204,0.1)"
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                        color: "rgba(204, 204, 204,0.1)"
                        }
                    }]
                    },
                    legend: {
                    display: true
                    },
                    elements: {
                    point: {
                        radius: 1
                    }
                    }
                };
                var multiLineData = {
                labels: result.hours_label,   // labels: [11, 12, 13, 14, 15, 16],
                datasets: result.datasets,
                // datasets: [{
                //     label: 'Dataset 1',
                //     data: [1, 1, 1, 1, 1, 1],
                //     borderColor: [
                //         '#587ce4'
                //     ],
                //     borderWidth: 2,
                //     fill: false
                //     },
                // ]
                };
            
                if ($("#chart-power-realtime").length) {
                var multiLineCanvas = $("#chart-power-realtime").get(0).getContext("2d");
                    new Chart(multiLineCanvas, {
                    type: 'line',
                    data: multiLineData,
                    options: options
                });
                }
           

            }  
        });
    }

  
  });