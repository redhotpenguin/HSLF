<?php
$this->breadcrumbs = array(
    'Push Notifications' => array('index'),
    'Send',
);
?>
 
<h1>Send a push notification</h1>
<?php
if ($model->sent == 'yes') {
    echo '<div class="important_alert_box">This notification was already sent!</div>';
}
?>
<?php
echo $this->renderPartial('_sendform', array('model' => $model, 'data' => $data));
?>
  
