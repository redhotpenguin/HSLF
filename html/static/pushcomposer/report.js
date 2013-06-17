jQuery(document).ready(report);


function report($){
    var pushStats = $("#pushStats"),
    getReport = function(pushId, _cb){
        $.get(pushmessage_ns.controller_url+'/jsonReport?pushId=' + pushId,_cb);
    },
    populateStatSection = function(pushId){
        getReport(pushId,function(stat){
            console.log(stat);

            var html = "<p>Direct Response(s): " + stat.direct_responses + "</p>";
            html += "<p>Sends: " + stat.sends + "</p>";

            $("#pushStats").html(html);

        });
    }
       
    if(pushmessage_ns.pushId){
        populateStatSection(pushmessage_ns.pushId);
    }
    else{
        pushStats.html('N/A');
    }
} // jquery ready/end
