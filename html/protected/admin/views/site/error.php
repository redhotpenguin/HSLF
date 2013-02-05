<?php
$this->pageTitle = Yii::app()->name . ' - Error';
$this->breadcrumbs = array(
    'Error',
);
?>
<h2>OMG</h2>
<?php
if (isset($code) && $code == 404) {
    echo '<h3>Page not found</h3>';
} else {
    echo "<h3>Something's gone horribly wrong</h3>";
    ?>



    <h6>Please report this error to the person in charge:</h6>
   
        <?php
        if (isset($code)) {
            echo '<pre>'.$code . ':';
            echo $message.'</pre>';
        }
        ?>
 
    <?php
}
?>


