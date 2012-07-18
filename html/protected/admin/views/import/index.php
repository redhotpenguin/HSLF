<?php

echo CHtml::form(CHtml::normalizeUrl(array('import/upload')), 'POST', array('enctype' => 'multipart/form-data'));

echo Chtml::label("csv only", "import");

echo CHtml::fileField("import");

echo Chtml::submitButton("Import");

echo Chtml::endForm();