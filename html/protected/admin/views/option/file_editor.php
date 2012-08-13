<?php
$this->breadcrumbs = array(
    'Options' => array('index'),
    'File Editor',
);
?>

<h1>File Editor</h1>

<?php
$this->widget('ext.FileEditor.Editor', array('files' => $edit_files));
?>
