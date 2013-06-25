<?php

/**
 * Custom DataColumn
 * Add placeholder property
 */
class DataColumn extends TbDataColumn {

    /***
     * @var $placeholder string - text input placeholder
     */
    public $placeholder;

    /**
     * Renders the filter cell.
     */
    public function renderFilterCell() {
        echo '<td><div class="filter-container">';
        $this->renderFilterCellContent();
        echo '</div></td>';
    }

    protected function renderFilterCellContent() {
        if (is_string($this->filter))
            echo $this->filter;
        elseif ($this->filter !== false && $this->grid->filter !== null && $this->name !== null && strpos($this->name, '.') === false) {
            if (is_array($this->filter))
                echo CHtml::activeDropDownList($this->grid->filter, $this->name, $this->filter, array('id' => false, 'prompt' => ''));
            elseif ($this->filter === null)
                echo CHtml::activeTextField($this->grid->filter, $this->name, array('id' => false, 'placeholder' => $this->placeholder));
        }
        else
            parent::renderFilterCellContent();
    }

}