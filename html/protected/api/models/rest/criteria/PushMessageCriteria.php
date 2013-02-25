<?php

class PushMessageCriteria extends CDbCriteria {

    private $pushMessage;
    private $tableAlias;
    private $sort;

    /**
     * PushMessageCriteria - extends CDbCriteria
     */
    public function __construct(PushMessage $pushMessage) {
        $this->pushMessage = $pushMessage;
        $this->tableAlias = $this->pushMessage->getTableAlias(false, false);

        // order by descending ID by default.
        $this->sort = array(
            'defaultOrder' => $this->tableAlias . '.creation_date DESC',
        );

        // set no default relations
        $this->with = array();
    }

    /**
     * Set tags (filter by relation)
     * @param array array of tag names
     */
    public function setTags($tagNames) {

        $this->addTagRelation();

        $i = 0;

        foreach ($tagNames as $tagName) {

            if ($i == 0)
                $operator = 'AND';
            else
                $operator = 'OR';

            $condition = 'tags.name=:tagName' . $i;

            $this->addCondition($condition, $operator);

            $this->params[":tagName{$i}"] = $tagName;

            ++$i;
        }
    }

    /**
     * Limit results ( bug )
     * @param integer $limit - limit number
     */
    public function setLimit($limit) {
        $this->limit = $limit;
    }

    /**
     * Set relations
     * @param array $relations - see CDBCriteria (with)
     */
    public function setRelations(array $relations = null) {
        $this->with = $relations;
    }

    /**
     * Add a relation
     * @param string  $relation - see CDBCriteria (with)
     */
    public function addRelation($relationName, $with = array()) {

        // make sure the relation is not already loaded
        if (in_array($relationName, $this->with))
            return false;

        if (!empty($with))
            $this->with[$relationName] = $with;
        else
            array_push($this->with, $relationName);
    }

    /**
     * Search records based on the criteria
     * @return return  arary of  PushMessage
     */
    public function search() {

        $activeDataProvider = new CActiveDataProvider($this->pushMessage, array(
                    'criteria' => $this,
                    'sort' => $this->sort,
                ));

        $activeDataProvider->pagination = false;

        try {
            $pushMessages = $activeDataProvider->getData();
        } catch (CDbException $cdbE) {
            //echo $cdbE->getMessage(); // debug
            $pushMessages = false;
        }



        return $pushMessages;
    }

    /**
     * Set the relation for tags
     */
    public function addTagRelation() {
        $with = array(
            'together' => true,
            'joinType' => ' JOIN',
        );

        $this->addRelation('tags', $with);
    }

}