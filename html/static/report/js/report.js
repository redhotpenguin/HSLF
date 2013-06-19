jQuery(document).ready(report);


function report($){
    var s1 = [];
    var ticks = [];
    
    $.get(report_ns.controller_url + '/report/monthlyJsonReport', function(report){
        var l  = report['sends'].length;
        
        for(var i = 0; i < l; i++){
            var r = report['sends'][i],
            total = r.android + r.ios;
            
            
            var d=new Date(Date.parse(r.date)),
            month = d.getMonth() + 1; // month sare zero based (jan = 0)
            
            s1.push(total);
            ticks.push( month + "/" + d.getDate() );
        }

     
        var plot1 = $.jqplot('monthlyPushChart', [s1], {
            axesDefaults:{
                min:0
            },
            seriesDefaults:{
                renderer:$.jqplot.BarRenderer,
                rendererOptions: {
                    fillToZero: false
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
                    return s1[pointIndex];
                }

            }, 
            cursor: {
                show: false
            }

        });
    
    
    });
} // jquery ready/end
