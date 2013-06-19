jQuery(document).ready(report);


function report($){
    
    function displayMonthlyPushReport(){
        var serie = [],
        ticks = [];
    
        $.get(report_ns.controller_url + '/report/monthlyPushJsonReport', function(report){
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

            $.jqplot('monthlyPushChart', [serie], {
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
                        return serie[pointIndex] + " pushes sent";
                    }

                }, 
                cursor: {
                    show: false
                }

            });
        });
    
    }  // displayMonthlyPushReport end
        
    function displayMonthlyUserRegistration(){
        var s1 = [];
        var ticks = [];
    
        $.get(report_ns.controller_url + '/report/monthlyJsonUserRegistration', function(report){
            
            
            $("#totalMonthlyUserCount").html('('  + report.total + ' total )');
            
            var l  = report['registrations'].length;

            for(var i = 0; i < l; i++){
                var r = report['registrations'][i],
                d=new Date(Date.parse(r.day)),
                month = d.getMonth() + 1; // month sare zero based (jan = 0)
  
                s1.push(r.total);
                ticks.push( month + "/" + d.getDate() );
            
            }
            
            $.jqplot('monthlyUserRegistrationChart', [s1], {
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

            }); 
        });
    
    }  // displayMonthlyUserRegistration end
    
    displayMonthlyPushReport();
    displayMonthlyUserRegistration();
} // jquery ready/end
