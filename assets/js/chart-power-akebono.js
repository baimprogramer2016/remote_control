

$(function() {
    /* ChartJS
     * -------
     * Data and config for chartjs
     */

    
    'use strict';

    function getChart(param_panel_id, param_color)
    {
      
      $.ajax({
        type: "GET",      
        url: 'getdatapanel.php?request_data=data_panel_id&panel_id='+param_panel_id+'&color='+param_color,
        success: function(responsdatapanel){
          // console.log(responsdatapanel);
          var responsekwh   = JSON.parse(responsdatapanel);
          var datajsonkwh   = responsekwh.datajson
          var datacolor     = responsekwh.color
          // console.log(datacolor);
            
            var data = {
              labels: [
                "1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31"
              ],
              datasets: [{
                label: 'kWh',
                data: datajsonkwh,
                backgroundColor: datacolor,
                borderColor: datacolor,
                // backgroundColor: [
                //   "yellow",'#B93160','#F2D1D1','#AEDBCE','#D75281','blue','#C689C6','#EED180','#FFF89C','#EAE509','#7DCE13','#5BB318','#2B7A0B','#5800FF','#0096FF','#00D7FF','#72FFFF','#EF5B0C','#A6D1E6','#B2A4FF','#61481C','#E6B325','#0078AA','#F6F6F6','#FFF80A','#EB1D36','#3330E4','#F637EC','#F5F0BB','#FBA1A1','#7D9D9C',
                // ],
                // borderColor: [
                //   "yellow",'#B93160','#F2D1D1','#AEDBCE','#D75281','blue','#C689C6','#EED180','#FFF89C','#EAE509','#7DCE13','#5BB318','#2B7A0B','#5800FF','#0096FF','#00D7FF','#72FFFF','#EF5B0C','#A6D1E6','#B2A4FF','#61481C','#E6B325','#0078AA','#F6F6F6','#FFF80A','#EB1D36','#3330E4','#F637EC','#F5F0BB','#FBA1A1','#7D9D9C',
                // ],
                borderWidth: 1,
                fill: false
              }]
            };
 
              // Get context with jQuery - using jQuery's .get() method.
            if(param_panel_id != "" || param_panel_id != null)
            {
              var barChartCanvas = $("#"+param_panel_id)//.get(0).getContext("2d");
              // This will get the first returned node in the jQuery collection.
              var barChart = new Chart(barChartCanvas, {
                type: 'bar',
                data: data,
                options:  {
                    scales: {
                      yAxes: [{
                        ticks: {
                          beginAtZero: true
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
                      display: false
                    },
                    elements: {
                      point: {
                        radius: 0
                      }
                    }
                  }
              });     
            }                                         
        }
      });
    }

    $.ajax({
      type: "GET",      
      url: 'getdatapanel.php?request_data=panel_id',
      success: function(responsepanel){
        // console.log(responsepanel);
        var datajson  = JSON.parse(responsepanel);
        datajson.forEach(function(datapanel){
          // console.log(datapanel.color);
          getChart(datapanel.panel_id, datapanel.color);
        });        
      }
    });
  
  });