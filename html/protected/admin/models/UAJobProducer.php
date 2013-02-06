<?php

/**
 * @todo: unit tests
 * @todo: refactor this class
 * JobProducer
 * Add jobs to a RabbitMQ queue
 * @author jonas
 */
class UAJobProducer {

    private $tenant;

    public function __construct(Tenant $tenant) {
        $this->tenant = $tenant;
    }

    public function pushUrbanAirshipMessage($alert, $searchAttributes, $extra) {
        throw new JobProducerException("This feature is not implemented.");

        /*
          // legacy code. might be useful sometime
          $payload = new Payload($alert, $searchAttributes, $extra);

          $searchAttributes['tenant_id'] = $this->tenant->id;

          $clientInfo = new ClientInfo($this->tenant->name, $this->tenant->email, $this->tenant->ua_api_key, $this->tenant->ua_api_secret);
         */
    }

}

