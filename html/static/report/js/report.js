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
            for(var i = 0; i < totalSends; i++){
                var report = pushReport['sends'][i],
                total = report.android + report.ios;
            
          
                pushSerie.push(total);
                ticks.push( moment(report.date).format('MMM YYYY') );
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
                seriesColors : ["#0088cc"], 
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
                    renderer: $.jqplot.EnhancedLegendRenderer,
                    show: true,
                    placement: "outsideGrid",
                    location: 's',
                    rendererOptions: {
                        numberRows: 1
                    }        
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
        ticks = [];
    
        $.get(report_ns.controller_url + '/report/jsonResponseReport', function(pushResponseReport){
                        
            var totalPushResponse  = pushResponseReport['responses'].length;

            for(var i = 0; i < totalPushResponse; i++){
                var response = pushResponseReport['responses'][i],
                total = response.ios.direct + response.android.direct;
 
                directSerie.push( total );
                influenceSerie.push(response.ios.influenced + response.android.influenced );
                ticks.push( moment(response.date).format('MMM YYYY') );
                
            }
            
            
            var graphOptions = {
                seriesColors : ["#0088cc", "#83CDF2"],                
                series:[

                {
                    label:'Direct'
                },

                {
                    label:'Influence'
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
                    renderer: $.jqplot.EnhancedLegendRenderer,
                    show: true,
                    placement: "outsideGrid",
                    location: 's',
                    rendererOptions: {
                        numberRows: 1
                    }        
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
                            return influenceSerie[pointIndex] + " influences";
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
            
            
            $("#totalMonthlyUserCount").html('('  + ( userRegistrationReport.android + userRegistrationReport.ios ).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' total)');
            
            var totalUserRegistration  = userRegistrationReport['registrations'].length;

            for(var i = 0; i < totalUserRegistration; i++){
                var report = userRegistrationReport['registrations'][i];
  

                androidSerie.push(report.android);
                iosSerie.push(report.ios);
                ticks.push( moment(report.date).format('MMM YYYY') );            
            }
            
            var graphOptions = {
                seriesColors : ["#0088cc", "#83CDF2"], 
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
                    renderer: $.jqplot.EnhancedLegendRenderer,
                    show: true,
                    placement: "outsideGrid",
                    location: 's',
                    rendererOptions: {
                        numberRows: 1
                    }
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
                            return androidSerie[pointIndex] + " Android users";
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