<?php

/**
 * Abstraction Layer to ease finding Active Records based model
 *
 * @author jonas
 */
abstract class ModelFinder {

    const GREATER_THAN = '>=:';
    const LESSER_THAN = '<=:';
    const EQUAL = '=:';
    const ASCENDANT = 'ASC';
    const DESCENDANT = 'DESC';

    private $attributes = array();
    private $relations = array();
    private $parameters = array();
    private $condition = "";
    private $order = "";

    protected final function addAttribute($attribute_key, $attribute_value) {
        $this->attributes[$attribute_key] = $attribute_value;
    }

    protected final function addCondition($condition_key, $condition_value, $sign = self::EQUAL) {
        if (!empty($this->condition)) {
            $and = ' and ';
        }

        $this->condition .= $and . $condition_key . $sign . $condition_value;
    }

    protected final function addParameter($parameter_key, $parameter_value) {
        $this->parameters[':' . $parameter_key] = $parameter_value;
    }

    protected final function setOrder($attribute, $order_type) {
        if ($order_type != self::DESCENDANT && $order_type != self::ASCENDANT)
            throw new InvalidArgumentException('Order type must be ' . self::DESCENDANT . ' or ' . self::ASCENDANT . '. Type given: ' . $order_type);

        $this->order = "$attribute $order_type";
    }

    public final function setRelations(array $relations) {
        $this->relations = $relations;
    }

    public final function search() {
        $criteria = array(
            'condition' => $this->condition,
            'params' => $this->parameters,
        );

        if ($this->order)
            $criteria['order'] = $this->order;

        return BallotItem::model()->with($this->relations)->findAllByAttributes($this->attributes, $criteria);
    }

}

?>
