<div class="row-fluid">


    <div class="span8">
        <?php echo $form->labelEx($model, 'item'); ?>
        <?php echo $form->textField($model, 'item', array('class' => 'span11', 'maxlength' => 1000, 'placeholder' => 'i.e Candidate or Measure\'s name')); ?>
        <?php echo $form->error($model, 'item'); ?>
    </div>

    <div class="span4">
        <?php
        echo $form->labelEx($model, 'item_type');
        echo $form->dropDownList($model, 'item_type', $model->getItemTypeOptions());
        echo $form->error($model, 'item_type');
        ?>
    </div>
</div>

<div class="clearfix"></div>

<hr/>

<div class="row-fluid">
    <?php
    $this->widget('ext.DistrictSelector.DistrictSelector', array(
        'model' => $model,
        'attribute' => 'district_id',
        'options' => array(
            'model_name' => 'Item',
        ),
    ));
    echo $form->error($model, 'district_id');
    ?>
</div>

<hr/>

<div  id="candidate_related_inputs" >
    <div class="row-fluid">
        <div class="span6">
            <?php
            echo $form->labelEx($model, 'first_name');
            echo $form->textField($model, 'first_name', array('class' => 'span12', 'maxlength' => 1024, 'placeholder' => ''));
            echo $form->error($model, 'first_name');
            ?>
        </div>
        <div class="span6">
            <?php
            echo $form->labelEx($model, 'last_name');
            echo $form->textField($model, 'last_name', array('class' => 'span12', 'maxlength' => 1024, 'placeholder' => ''));
            echo $form->error($model, 'last_name');
            ?>
        </div>
    </div>
</div>


<div id ="measure_related_inputs">
    <div class="row-fluid">
        <div class="span6">
            <?php
            echo $form->labelEx($model, 'measure_number');
            echo $form->textField($model, 'measure_number', array('class' => 'span12', 'maxlength' => 24, 'placeholder' => 'Measure Number'));
            echo $form->error($model, 'measure_number');
            ?>
        </div>
        <div class="span6">
            <?php
            echo $form->labelEx($model, 'friendly_name');
            echo $form->textField($model, 'friendly_name', array('class' => 'span12', 'maxlength' => 1024, 'placeholder' => 'Friendly Name'));
            echo $form->error($model, 'friendly_name');
            ?>
        </div>
    </div>
</div>

<div class="clearfix"></div>