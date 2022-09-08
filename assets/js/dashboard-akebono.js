(function($) {
    'use strict';
    $.fn.andSelf = function() {
      return this.addBack.apply(this, arguments);
    }
    $(function() {
    
      //water
      if ($("#water-chart").length) {
        //get data
        $.ajax({
          type: "GET",
          // data: "?request_data=datapower",
          url: 'getdatachart.php?request_data=datawater',
          success: function(responsedatawater){
          // console.log(responsedatawater);
          var dataparsewater  = JSON.parse(responsedatawater);
          var datalabel       = dataparsewater.label;
          var datawater       = dataparsewater.datawater;
          var totaldailyuse   = dataparsewater.totaldailyuse;
          var datacolor       = dataparsewater.color;
        
          var dataallwater = {
            labels:datalabel, // labels:["a","b","c","d","e","f","g"],
            datasets: [{
                data: datawater,//[55, 25, 20,55, 25, 20,10],
                // backgroundColor: [
                //   "#3330E4","#7DCE13","#003865","#FAEA48","#EF5B0C","#D61C4E","#CCD6A6",
                // ]
                backgroundColor : datacolor,
              }
            ]
          };
          
            var areaOptions = {
              responsive: true,
              maintainAspectRatio: true,
              segmentShowStroke: false,
              cutoutPercentage: 70,
              elements: {
                arc: {
                    borderWidth: 0
                }
              },      
              legend: {
                display: false
              },
              tooltips: {
                enabled: true
              }
            }
            var transactionhistoryChartPlugins = {
              beforeDraw: function(chart) {
                var width = chart.chart.width,
                    height = chart.chart.height,
                    ctx = chart.chart.ctx;
            
                ctx.restore();
                var fontSize = 1;
                ctx.font = fontSize + "rem sans-serif";
                ctx.textAlign = 'left';
                ctx.textBaseline = "middle";
                ctx.fillStyle = "#ffffff";
            
                var text = totaldailyuse,  //5.400
                    textX = Math.round((width - ctx.measureText(text).width) / 2),
                    textY = height / 2.4;
            
                ctx.fillText(text, textX, textY);
      
                ctx.restore();
                var fontSize = 0.75;
                ctx.font = fontSize + "rem sans-serif";
                ctx.textAlign = 'left';
                ctx.textBaseline = "middle";
                ctx.fillStyle = "#6c7293";
      
                var texts = "(K) M3", 
                    textsX = Math.round((width - ctx.measureText(text).width) / 1.93),
                    textsY = height / 1.7;
            
                ctx.fillText(texts, textsX, textsY);
                ctx.save();
              }
            }
            var transactionhistoryChartCanvas = $("#water-chart").get(0).getContext("2d");
            var transactionhistoryChart = new Chart(transactionhistoryChartCanvas, {
              type: 'doughnut',
              data: dataallwater,
              options: areaOptions,
              plugins: transactionhistoryChartPlugins
            });
          }
       });        
         
      }
      //power
      if ($("#power-chart").length) {
        //get data
        $.ajax({
          type: "GET",
          // data: "?request_data=datapower",
          url: 'getdatachart.php?request_data=datapower',
          success: function(responsedatapower){
          // console.log(responsedatapower);
          var dataparsepower   = JSON.parse(responsedatapower);
          var datalabel   = dataparsepower.label;
          var totalkwh    = dataparsepower.totalkwh;
          var datacolor   = dataparsepower.color;
          var datakwh   = dataparsepower.datakwh;
        
          var datapower = {
            labels:datalabel, // labels:["a","b","c","d","e","f","g"],
            datasets: [{
                data: datakwh,//[55, 25, 20,55, 25, 20,10],
                // backgroundColor: [
                //   "#3330E4","#7DCE13","#003865","#FAEA48","#EF5B0C","#D61C4E","#CCD6A6",
                // ]
                backgroundColor : datacolor,
              }
            ]
          };
          
            var areaOptions = {
              responsive: true,
              maintainAspectRatio: true,
              segmentShowStroke: false,
              cutoutPercentage: 70,
              elements: {
                arc: {
                    borderWidth: 0
                }
              },      
              legend: {
                display: false
              },
              tooltips: {
                enabled: true
              }
            }
            var transactionhistoryChartPlugins = {
              beforeDraw: function(chart) {
                var width = chart.chart.width,
                    height = chart.chart.height,
                    ctx = chart.chart.ctx;
            
                ctx.restore();
                var fontSize = 1;
                ctx.font = fontSize + "rem sans-serif";
                ctx.textAlign = 'left';
                ctx.textBaseline = "middle";
                ctx.fillStyle = "#ffffff";
            
                var text = totalkwh, 
                    textX = Math.round((width - ctx.measureText(text).width) / 2),
                    textY = height / 2.4;
            
                ctx.fillText(text, textX, textY);
      
                ctx.restore();
                var fontSize = 0.75;
                ctx.font = fontSize + "rem sans-serif";
                ctx.textAlign = 'left';
                ctx.textBaseline = "middle";
                ctx.fillStyle = "#6c7293";
      
                var texts = "kWh", 
                    textsX = Math.round((width - ctx.measureText(text).width) / 1.93),
                    textsY = height / 1.7;
            
                ctx.fillText(texts, textsX, textsY);
                ctx.save();
              }
            }
            var transactionhistoryChartCanvas = $("#power-chart").get(0).getContext("2d");
            var transactionhistoryChart = new Chart(transactionhistoryChartCanvas, {
              type: 'doughnut',
              data: datapower,
              options: areaOptions,
              plugins: transactionhistoryChartPlugins
            });
          }
       });        
         
      }
      //compressor
      if ($("#compressor-chart").length) {
        //get data
        $.ajax({
          type: "GET",
          // data: "?request_data=datapower",
          url: 'getdatachart.php?request_data=datacompressor',
          success: function(responsedatacompressor){
          //  console.log(responsedatacompressor);
          var dataparsecompressor   = JSON.parse(responsedatacompressor);
          var datalabel             = dataparsecompressor.label;
          var totalcompressor       = dataparsecompressor.totalcompressor;
          var datacolor             = dataparsecompressor.color;
          var datacompressor        = dataparsecompressor.datacompressor;
          console.log(totalcompressor)
            
          var datacompre = {
            labels:datalabel, // labels:["a","b","c","d","e","f","g"],
            datasets: [{
                data: datacompressor,//[55, 25, 20,55, 25, 20,10],
                // backgroundColor: [
                //   "#3330E4","#7DCE13","#003865","#FAEA48","#EF5B0C","#D61C4E","#CCD6A6",
                // ]
                backgroundColor : datacolor,
              }
            ]
          };
          
            var areaOptions = {
              responsive: true,
              maintainAspectRatio: true,
              segmentShowStroke: false,
              cutoutPercentage: 70,
              elements: {
                arc: {
                    borderWidth: 0
                }
              },      
              legend: {
                display: false
              },
              tooltips: {
                enabled: true
              }
            }
            var transactionhistoryChartPlugins = {
              beforeDraw: function(chart) {
                var width = chart.chart.width,
                    height = chart.chart.height,
                    ctx = chart.chart.ctx;
            
                ctx.restore();
                var fontSize = 1;
                ctx.font = fontSize + "rem sans-serif";
                ctx.textAlign = 'left';
                ctx.textBaseline = "middle";
                ctx.fillStyle = "#ffffff";
            
                var text = totalcompressor, 
                    textX = Math.round((width - ctx.measureText(text).width) / 2),
                    textY = height / 2.4;
            
                ctx.fillText(text, textX, textY);
      
                ctx.restore();
                var fontSize = 0.75;
                ctx.font = fontSize + "rem sans-serif";
                ctx.textAlign = 'left';
                ctx.textBaseline = "middle";
                ctx.fillStyle = "#6c7293";
      
                var texts = "L/Min",
                    textsX = Math.round((width - ctx.measureText(text).width) / 1.93),
                    textsY = height / 1.7;
            
                ctx.fillText(texts, textsX, textsY);
                ctx.save();
              }
            }
            var transactionhistoryChartCanvas = $("#compressor-chart").get(0).getContext("2d");
            var transactionhistoryChart = new Chart(transactionhistoryChartCanvas, {
              type: 'doughnut',
              data: datacompre,
              options: areaOptions,
              plugins: transactionhistoryChartPlugins
            });
          }
       });        
         
      }
     //machine
     if ($("#machines-chart").length) {
      //get data
      $.ajax({
        type: "GET",
        // data: "?request_data=datapower",
        url: 'getdatachart.php?request_data=datamachines',
        success: function(responsedatamachines){
        // console.log(responsedatapower);
        var dataparsemachines   = JSON.parse(responsedatamachines);
        var datalabel           = dataparsemachines.label;
        var totalmachines       = dataparsemachines.totalmachines;
        var datacolor           = dataparsemachines.color;
        var datamachines        = dataparsemachines.datamachines;
      
        var datamachines = {
          labels:datalabel, // labels:["a","b","c","d","e","f","g"],
          datasets: [{
              data: datamachines,//[55, 25, 20,55, 25, 20,10],
              // backgroundColor: [
              //   "#3330E4","#7DCE13","#003865","#FAEA48","#EF5B0C","#D61C4E","#CCD6A6",
              // ]
              backgroundColor : datacolor,
            }
          ]
        };
        
          var areaOptions = {
            responsive: true,
            maintainAspectRatio: true,
            segmentShowStroke: false,
            cutoutPercentage: 70,
            elements: {
              arc: {
                  borderWidth: 0
              }
            },      
            legend: {
              display: false
            },
            tooltips: {
              enabled: true
            }
          }
          var transactionhistoryChartPlugins = {
            beforeDraw: function(chart) {
              var width = chart.chart.width,
                  height = chart.chart.height,
                  ctx = chart.chart.ctx;
          
              ctx.restore();
              var fontSize = 1;
              ctx.font = fontSize + "rem sans-serif";
              ctx.textAlign = 'left';
              ctx.textBaseline = "middle";
              ctx.fillStyle = "#ffffff";
          
              var text = totalmachines, 
                  textX = Math.round((width - ctx.measureText(text).width) / 2),
                  textY = height / 2.4;
          
              ctx.fillText(text, textX, textY);
    
              ctx.restore();
              var fontSize = 0.75;
              ctx.font = fontSize + "rem sans-serif";
              ctx.textAlign = 'left';
              ctx.textBaseline = "middle";
              ctx.fillStyle = "#6c7293";
    
              var texts = "Qty", 
                  textsX = Math.round((width - ctx.measureText(text).width) / 1.93),
                  textsY = height / 1.7;
          
              ctx.fillText(texts, textsX, textsY);
              ctx.save();
            }
          }
          var transactionhistoryChartCanvas = $("#machines-chart").get(0).getContext("2d");
          var transactionhistoryChart = new Chart(transactionhistoryChartCanvas, {
            type: 'doughnut',
            data: datamachines,
            options: areaOptions,
            plugins: transactionhistoryChartPlugins
          });
        }
     });        
       
      }
      });
  })(jQuery);