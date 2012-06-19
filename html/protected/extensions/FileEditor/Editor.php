<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Editor extends CWidget {

    public $options = array();
    public $files;
    private $current_file_name;
    private $save_result = '';

    public function run() {
        if (isset($_REQUEST['file_selector']) && in_array($_REQUEST['file_selector'], $this->files) ) {
            Yii::app()->session['current_file_selected'] = $_REQUEST['file_selector'];
            $this->current_file_name =  $_REQUEST['file_selector'];
            
        } elseif (!empty(Yii::app()->session['current_file_selected'])){
             $this->current_file_name  = Yii::app()->session['current_file_selected'];
        }
        else{
            $this->current_file_name   = $this->files[0];
        }
 
        if (!file_exists($this->current_file_name)) {
            echo 'File not found: ' . $this->current_file_name;
            return false;
        }

        if (!is_writable($this->current_file_name)) {
            echo 'Write permissions denied for: ' . $this->current_file_name;
            return false;
        }

        if (isset($_POST['FileContent'])) {
            $save_result = $this->saveFile($this->current_file_name, $_POST['FileContent']);
            if($save_result > 0)
                $this->save_result =  'File Saved';
            else
                $this->save_result = 'Could not save the file';
        }

        $read_file_handle = fopen($this->current_file_name, 'r');

        $file_content = fread($read_file_handle, filesize($this->current_file_name));

        $this->printEditorWidget($file_content);
    }

    private function printEditorWidget($file_content) {
        $this->startForm();
        $this->printHeader($this->current_file_name);
        $this->printFileEditor($file_content);
        $this->printSaveButton();
        $this->endForm();
        $this->printFilePicker($this->current_file_name);
        if($this->save_result)
            $this->printSaveResult($this->save_result);
    }

    private function printFileEditor($file_content) {
        echo "<textarea name='FileContent'  rows='30' cols='120'>$file_content</textarea>";
    }

    private function printHeader($header) {
        echo '<h3> Editing: ' . $this->extractFileName($header) . '</h3>';
    }

    private function printSaveButton() {
        echo '<input type="submit" value="Save" />';
    }

    private function startForm($method = 'POST', $action = '#') {
        echo "<form  method='$method' action='$action'> ";
    }

    private function endForm() {
        echo '</form>';
    }

    private function saveFile($current_current_file_name, $file_content) {
        $write_file_handle = fopen($current_current_file_name, 'w');
        return fwrite($write_file_handle, $file_content);
    }

    private function printFilePicker($current_selection) {
        ?>
        <form method='POST' action='#' name='file_picker'>
            <select name='file_selector' onchange ="document.file_picker.submit()" >

                <?php
                foreach ($this->files as $file_name) {
                    if ($file_name == $current_selection)
                        $selected = 'selected="selected"';
                    else
                        $selected = '';
                    echo '<option ' . $selected . ' value="' . $file_name . '">' . $this->extractFileName($file_name) . '</option>';
                }
                ?>

            </select>
        </form>
        <?php
    }
    
    private function printSaveResult($result){
        echo $result;
    }
    
    private function extractFileName($fq_file){
        return end( explode("\\", $fq_file) ); // return the last element of the array
    }

}