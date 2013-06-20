jQuery(document).ready(report);


function report($){
    
    var buildGraph = function(target, series, ticks, graphOptions){
        return $.jqplot(target, series, graphOptions); 
    },
    buildMonthlyPushChart = function(){
        $.get(report_ns.controller_url + '/report/jsonPushReport', function(report){
            var serie = [],
            ticks = [];
        
            var l  = report['sends'].length;
            var prevTotal = 1;
            for(var i = 0; i < l; i++){
                var r = report['sends'][i],
                total = r.android + r.ios;
            
                if(total == 0 &&  prevTotal  == 0  ){ // skip consecutive 0s
                    continue;
                }    
                       
                var d=new Date(Date.parse(r.date)),
                month = d.getMonth() + 1; // month sare zero based (jan = 0)
            
                serie.push(total);
                ticks.push( month + "/" + d.getDate() );
            
                prevTotal =  total;
            }
       
            var graphOptions =  {
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
                        if(seriesIndex == 0){ // direct
                        //   return directSerie[pointIndex] + " directs";
                        }else{ // influenced
                        //  return influenceSerie[pointIndex] + " influenced";
                        }
                       
                    }

                }, 
                cursor: {
                    show: false
                }

            },
            graph = buildGraph("monthlyPushChart", [serie], ticks, graphOptions);
        });
    },
    buildMonthlyUserRegistrationChart = function(){
        var s1 = [];
        var ticks = [];
    
        $.get(report_ns.controller_url + '/report/jsonUserRegistrationReport', function(report){
            
            
            $("#totalMonthlyUserCount").html('('  + report.total + ' total)');
            
            var l  = report['registrations'].length;

            for(var i = 0; i < l; i++){
                var r = report['registrations'][i],
                d=new Date(Date.parse(r.day)),
                month = d.getMonth() + 1; // month sare zero based (jan = 0)
  
                s1.push(r.total);
                ticks.push( month + "/" + d.getDate() );
            
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

                legend: {
                    show: false
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
                        return s1[pointIndex] + " user registrations";
                    }

                }, 
                cursor: {
                    show: false
                }

            };
            var graph = buildGraph("monthlyUserRegistrationChart", [s1], ticks, graphOptions);

        });
    },
    buildMonthlyUserResponseChart = function(){
        var directSerie = [], // red
        influenceSerie = [], // blue
        ticks = [];
    
        $.get(report_ns.controller_url + '/report/jsonResponseReport', function(report){
                        
            var l  = report['responses'].length;

            for(var i = 0; i < l; i++){
                var response = report['responses'][i] ;
                var date=new Date(Date.parse(response.date)),
                month = date.getMonth() + 1; // month sare zero based (jan = 0)
                directSerie.push( response.ios.direct + response.android.direct );
                influenceSerie.push(response.ios.influenced + response.android.influenced );
                ticks.push( month + "/" + date.getDate() );
            }
            
            
            var graphOptions = {
                seriesColors : ["red","blue"],
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

          
            };
            
            var graph = buildGraph("monthlyUserResponseChart", [directSerie, influenceSerie], ticks, graphOptions);
        });
    };
    
    
    
 
    
    buildMonthlyPushChart();
    buildMonthlyUserRegistrationChart();
    buildMonthlyUserResponseChart();
    
} // jquery ready/end
