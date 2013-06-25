<?php

Yii::import('bootstrap.widgets.TbGridView');

/**
 * Custom GridView that extends @TbGridView
 * Uses custom @DataColumn
 */
class GridView extends TbGridView {

    /**
     * Creates column objects and initializes them.
     * Use @DataColumn
     */
    protected function initColumns() {
        foreach ($this->columns as $i => $column) {
            if (is_array($column) && !isset($column['class']))
                $this->columns[$i]['class'] = 'backend.extensions.ExtendedWidgets.DataColumn';
        }

        parent::initColumns();
    }

}