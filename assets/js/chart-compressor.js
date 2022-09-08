function chartQuage(paramCanvas, paramValue)
{
var myConfig = {
 	type: "gauge",
 	globals: {
 	  fontSize: 12
 	},
 	plotarea:{
 	  marginTop:30
 	},
 	plot:{
 	  size:'100%',
 	  valueBox: {
 	    placement: 'center',
 	    text:'%v', //default
 	    fontSize:20,
 	 
 	  }
 	},
     backgroundColor:"#191c24",
  tooltip:{
    borderRadius:30,
	backgroundColor:"#191c24",
  },
 	scaleR:{
        backgroundColor:"#cedadc",
	  aperture:180,
	  minValue:0,
	  maxValue:10,
	  step:2,
      
	  center:{
	    visible:false,
        
	  },
	  tick:{
	    visible:false
        
	  },
	  item:{
        color:"#cedadc",
        fontSize:"15px",
	    offsetR:0,
	    rules:[
	      {
	        rule:'%i == 9',
	        offsetX:15
	      }
	    ]
        
	  },
      backgroundColor:"#191c24",
	  labels:['0','2','4','6','8','10'],
      
	  ring:{
        backgroundColor:"#C70039 #581845 ",
	    size:35,
	    // rules:[
	 
	    //   {
        //     rule:'%v > 0 && %v <= 2',
	    //     backgroundColor:'#93E1D8'
	    //   },
	    //   {
	    //     rule:'%v > 2 && %v <= 4',
	    //     backgroundColor:'#FFA69E'
	    //   },
	    //   {
	    //     rule:'%v > 4 && %v <= 6',
	    //     backgroundColor:'#AA4465'
	    //   },
	    //   {
	    //     rule:'%v > 6 && %v <= 8',
	    //     backgroundColor:'#861657'
	    //   },
	    //   {
	    //     rule:'%v > 8 && %v <= 10',
	    //     backgroundColor:'#a71e4a'
	    //   }      ,
	        
	    // ]
	  }
 	},
  refresh:{  
      type:"feed",
      transport:"js",
      url:"feed()",
      interval:100,
      resetTimeout:1000,
      
  },
	series : [
		{
		values : [paramValue], // starting value
		backgroundColor:'white',
	    indicator:[10,10,10,10,0.75],
	    animation:{  
        effect:2,
        method:1,
        sequence:2,
        speed: 500,
        
     },
		}
	]
};

zingchart.render({ 
	id : paramCanvas, 
	data : myConfig, 
	height: 300, 
	width: '100%',
    
});
}

$.ajax({
    type: "GET",      
    url: 'getdatacompressor.php?request_data=compressore_code',
    success: function(responsecompre){
      
    var datajsoncompre  = JSON.parse(responsecompre);
    
    datajsoncompre.forEach(function(datacompre){
        // console.log(datacompre.key);
        // getChart(datapanel.panel_id, datapanel.color);
        chartQuage(datacompre.key,datacompre.value); 
    });        
    }
  });



