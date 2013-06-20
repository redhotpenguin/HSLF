jQuery(document).ready(report);


function report($){
    
    var buildGraph = function(target, series, graphOptions){
        return $.jqplot(target, series, graphOptions); 
    },
    buildMonthlyPushChart = function(){
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
    buildMonthlyUserRegistrationChart = function(){
        var userRegistrationSerie = [],
        ticks = [];
    
        $.get(report_ns.controller_url + '/report/jsonUserRegistrationReport', function(userRegistrationReport){
            
            
            $("#totalMonthlyUserCount").html('('  + userRegistrationReport.total + ' total)');
            
            var totalUserRegistration  = userRegistrationReport['registrations'].length;

            for(var i = 0; i < totalUserRegistration; i++){
                var report = userRegistrationReport['registrations'][i],
                registrationDate = new Date(Date.parse(report.day)),
                month = registrationDate.getMonth() + 1; // month sare zero based (jan = 0)
  
                userRegistrationSerie.push(report.total);
                ticks.push( month + "/" + registrationDate.getDate() );
            
            }
            
            var graphOptions = {
                seriesColors : ["#0088cc"],
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
                series:[
                {
                    label:'User Registrations'
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
                        return userRegistrationSerie[pointIndex] + " user registrations";
                    }

                }, 
                cursor: {
                    show: false
                }

            };
            var graph = buildGraph("monthlyUserRegistrationChart", [userRegistrationSerie], graphOptions);
        });
    },
    buildMonthlyUserResponseChart = function(){
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
    };
    
    
    
 
    
    buildMonthlyPushChart();
    buildMonthlyUserResponseChart();
    buildMonthlyUserRegistrationChart();

    
} // jquery ready/end