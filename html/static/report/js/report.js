jQuery(document).ready(report);


function report($){
    
    var buildGraph = function(target, series, graphOptions){
        return $.jqplot(target, series, graphOptions); 
    },
    buildMonthlyPushReport = function(){
        $.get(report_ns.controller_url + '/report/jsonPushReport', function(pushReport){
            var pushSerie = [],
            ticks = [];
        
            var totalSends  = pushReport['sends'].length;
            var prevTotal = 1;
            for(var i = 0; i < totalSends; i++){
                var report = pushReport['sends'][i],
                total = report.android + report.ios;
            
                if(total == 0 &&  prevTotal  == 0  ){ // skip consecutive 0s
                    continue;
                }    
                       
                var d=new Date(Date.parse(report.date)),
                month = d.getMonth() + 1; // month sare zero based (jan = 0)
            
                pushSerie.push(total);
                ticks.push( month + "/" + d.getDate() );
            
                prevTotal =  total;
            }
       
            var graphOptions =  {
                series:[
                {
                    label:'Pushes Sent'
                },
                ],
                animate: true,
                axesDefaults:{
                    min:0
                },
                seriesColors:["red","blue"],
                seriesDefaults:{
                    renderer:$.jqplot.BarRenderer,
                    rendererOptions: {
                        fillToZero: false,
                        animation: {
                            speed: 1000
                        }
                    }
                },

                legend: {
                    show: true,
                    placement: "outsideGrid"
                    
                },
                axes: {
                    xaxis: {
                        renderer: $.jqplot.CategoryAxisRenderer,
                        ticks: ticks,
                        tickOptions:{
                            showGridline: false
                        }
                    },
                    yaxis: {
                        pad: 1.05,
                        tickOptions: {
                            formatString: '%d',
                            showGridline: true
                        }
                    }
                },
                highlighter: {
                    show: true,
                    sizeAdjust: 7.5,
                    formatString: "%d",
                    tooltipContentEditor: function(str, seriesIndex, pointIndex, jqPlot) {
                        return pushSerie[pointIndex] + " pushes sent";
                    }

                }, 
                cursor: {
                    show: false
                }

            },
            graph = buildGraph("monthlyPushChart", [pushSerie], graphOptions);
        });
    },
    buildMonthlyUserResponseReport = function(){
        var directSerie = [],
        influenceSerie = [],
        ticks = [],
        prevTotal = 1;
    
        $.get(report_ns.controller_url + '/report/jsonResponseReport', function(pushResponseReport){
                        
            var totalPushResponse  = pushResponseReport['responses'].length;

            for(var i = 0; i < totalPushResponse; i++){
                var response = pushResponseReport['responses'][i],
                date = new Date(Date.parse(response.date)),
                total = response.ios.direct + response.android.direct;
                
                if(total == 0 &&  prevTotal  == 0  ){ // skip consecutive 0s
                    continue;
                }    
                     
                
                var month = date.getMonth() + 1; // month sare zero based (jan = 0)
                directSerie.push( total );
                influenceSerie.push(response.ios.influenced + response.android.influenced );
                ticks.push( month + "/" + date.getDate() );
                prevTotal = total;
            }
            
            
            var graphOptions = {
                seriesColors : ["#0088cc", "#8800cc"],                
                series:[

                {
                    label:'Direct'
                },

                {
                    label:'Influenced'
                },
                ],
                stackSeries: true,
                animate: true,
                axesDefaults:{
                    min:0
                },
                seriesDefaults:{
                    renderer:$.jqplot.BarRenderer,
                    rendererOptions: {
                        fillToZero: false,
                        animation: {
                            speed: 1000
                        }
                    }
                },

                legend: {
                    show: true,
                    placement: "outsideGrid"
                    
                },
                axes: {
                    xaxis: {
                        renderer: $.jqplot.CategoryAxisRenderer,
                        ticks: ticks,
                        tickOptions:{
                            showGridline: false
                        }
                    },
                    yaxis: {
                        pad: 1.05,
                        tickOptions: {
                            formatString: '%d',
                            showGridline: true
                        }
                    }
                },
                highlighter: {
                    show: true,
                    sizeAdjust: 7.5,
                    formatString: "%d",
                    tooltipContentEditor: function(str, seriesIndex, pointIndex, jqPlot) {
                        if(seriesIndex == 0){ // direct
                            return directSerie[pointIndex] + " directs";
                        }else{ // influenced
                            return influenceSerie[pointIndex] + " influenced";
                        }
                       
                    }

                }, 
                cursor: {
                    show: false
                }

          
            },
            graph = buildGraph("monthlyUserResponseChart", [directSerie, influenceSerie], graphOptions);
        });
    },
    buildMonthlyUserRegistrationReport = function(){
        var androidSerie = [],
        iosSerie = [],
        ticks = [];
    
        $.get(report_ns.controller_url + '/report/jsonUserRegistrationReport', function(userRegistrationReport){
            
            
            $("#totalMonthlyUserCount").html('('  + ( userRegistrationReport.android + userRegistrationReport.ios ) + ' total)');
            
            var totalUserRegistration  = userRegistrationReport['registrations'].length;

            for(var i = 0; i < totalUserRegistration; i++){
                var report = userRegistrationReport['registrations'][i],
                registrationDate = new Date(Date.parse(report.day)),
                month = registrationDate.getMonth() + 1; // month sare zero based (jan = 0)
  
                
                androidSerie.push(report.android);
                iosSerie.push(report.ios);
                
                ticks.push( month + "/" + registrationDate.getDate() );
            
            }
            
            var graphOptions = {
                seriesColors : ["#0088cc","#00cc88"],
                animate: true,
                stackSeries: true,
                axesDefaults:{
                    min:0
                },
                seriesDefaults:{
                    renderer:$.jqplot.BarRenderer,
                    rendererOptions: {
                        fillToZero: false,
                        animation: {
                            speed: 1000
                        }
                    }
                },
                series:[
                {
                    label:'Android'
                },
                {
                    label:'iOs'
                }
                ],
                legend: {
                    show: true,
                    placement: "outsideGrid"
                    
                },
                axes: {
                    xaxis: {
                        renderer: $.jqplot.CategoryAxisRenderer,
                        ticks: ticks,
                        tickOptions:{
                            showGridline: false
                        }
                    },
                    yaxis: {
                        pad: 1.05,
                        tickOptions: {
                            formatString: '%d',
                            showGridline: true
                        }
                    }
                },
                highlighter: {
                    show: true,
                    sizeAdjust: 7.5,
                    formatString: "%d",
                    tooltipContentEditor: function(str, seriesIndex, pointIndex, jqPlot) {
                        if(seriesIndex == 0){
                            return androidSerie[pointIndex] + " android users";
                        }else{
                            return iosSerie[pointIndex] + " iOs users";
                        }
                        
                    }

                }, 
                cursor: {
                    show: false
                }

            };
            var graph = buildGraph("monthlyUserRegistrationChart", [androidSerie, iosSerie], graphOptions);
        });
    };
    
    
    
 
    
    buildMonthlyPushReport();
    buildMonthlyUserResponseReport();
    buildMonthlyUserRegistrationReport();

    
} // jquery ready/end