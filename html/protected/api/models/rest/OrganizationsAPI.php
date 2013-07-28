<?php

class OrganizationsAPI extends APIBase {

    public function __construct() {
        parent::__construct(new Organization);
    }

    public function getSingle($tenantId, $id, $arguments = array()) {
        $relations = array();
        $options = array();


        if (isset($arguments['relations'])) {

            $requestedRelations = explode(',', $arguments['relations']);

            if (in_array('tags', $requestedRelations)) {
                array_push($relations, 'tags');
            }

            if (in_array('contacts', $requestedRelations)) {
                array_push($relations, 'contacts');
                $options['order'] = 'position ASC';
            }
        }

        try {

            $result = $this->model->with($relations)->findByPk($id, $options);

            if (!$result) {
                throw new RestException(404);
            }
        } catch (RestException $cdbE) {
            throw $cdbE;
        } catch (Exception $cdbE) {
            throw new RestException(500);
        }

        return $result;
    }

}