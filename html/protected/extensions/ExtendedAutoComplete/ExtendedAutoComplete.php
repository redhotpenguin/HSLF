<?php

Yii::import('zii.widgets.jui.CJuiAutoComplete');


class ExtendedAutoComplete extends CJuiAutoComplete
{
    /**
     * @var string the chain of method calls that would be appended at the end of the autocomplete constructor.
     * For example, ".result(function(...){})" would cause the specified js function to execute
     * when the user selects an option.
     */
    public $methodChain;
    /**
     * Run this widget.
     * This method registers necessary javascript and renders the needed HTML code.
     */
    public function run()
    {
        list($name,$id)=$this->resolveNameID();
 
        if(isset($this->htmlOptions['id']))
            $id=$this->htmlOptions['id'];
        else
            $this->htmlOptions['id']=$id;
 
        if(isset($this->htmlOptions['name']))
            $name=$this->htmlOptions['name'];
 
        if($this->hasModel())
            echo CHtml::activeTextField($this->model,$this->attribute,$this->htmlOptions);
        else
            echo CHtml::textField($name,$this->value,$this->htmlOptions);
 
        if($this->sourceUrl!==null)
            $this->options['source']=CHtml::normalizeUrl($this->sourceUrl);
        else
            $this->options['source']=$this->source;
 
        $options=CJavaScript::encode($this->options);
 
        $js = "jQuery('#{$id}').autocomplete($options){$this->methodChain};";
 
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__.'#'.$id, $js);
    }
}