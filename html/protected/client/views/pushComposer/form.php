<?php
$this->secondaryNav['name'] = 'Push Composer';
$this->secondaryNav['url'] = array('pushComposer/index');

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/static/global/js/form/jquery.multipage.js');
$cs->registerCssFile($baseUrl . '/static/global/css/jquery.multipage.css');
?>
<h3>push composer</h3>

<script type="text/javascript">
    $(window).ready(function() {
        $('#multipage').multipage();
        $('form').submit(function(){ alert("Submitted!"); return false;});
    });

    function generateTabs(tabs) { 

        html = '';
        for (var i in tabs) { 
            tab = tabs[i];
            html = html + '<li class="multipage_tab"><a href="#" onclick="return $(\'#multipage\').gotopage(' + tab.number + ');">' + tab.title + '</a></li>';				
        }
        $('<ul class="multipage_tabs" id="multipage_tabs">'+html+'<div class="clearer"></div></ul>').insertBefore('#multipage');
    }
    function setActiveTab(selector,page) { 
        $('#multipage_tabs li').each(function(index){ 
            if ((index+1)==page) { 
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        });			
    }

    function transition(from,to) {
        $(from).fadeOut('fast',function(){$(to).fadeIn('fast');});

    }
    function textpages(obj,page,pages) { 
        $(obj).html(page + ' of ' + pages);
    }

</script>
<form id="multipage">
    <?php
    echo $this->renderPartial('composer/_message');

    echo $this->renderPartial('composer/_recipients');

    echo $this->renderPartial('composer/_action');
    ?>
</form>