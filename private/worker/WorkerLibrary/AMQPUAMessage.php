<?php

/**
 * @todo: When Yii 2.0 is released: update this class to uses namespace
 */

/**
 * AMQP urbanairship - message structure - UTF8 should be used
 *
 * @author jonas
 */
class AMQPUAMessage {

    /**
     * Client info
     * @var Client 
     */
    public $clientInfo;

    /**
     * Payload
     * @var payload 
     */
    public $payload;

    /**
     * AMQPUAMessage
     * @param Client $clientInfo client info
     * @param Payload $payload  payload
     */
    public function __construct(ClientInfo $clientInfo, Payload $payload) {
        $this->clientInfo = $clientInfo;
        $this->payload = $payload;
    }

    /**
     * Helper - Unserialize a message
     * @param string  $serializedMsg serialized messsage
     * @return AMQPUAMessage AMQPUAMessage object
     */
    public static function unserialize($serializedMsg) {
        $amqpUaMessage = unserialize($serializedMsg);
        if ($amqpUaMessage == false) {
            throw new Exception("could not deserialize message");
        } else {
            return $amqpUaMessage;
        }
    }

    /**
     * Helper - serialize a message
     * @return string serialized AMQPUAMessage object
     */
    public function serialize() {
        return serialize($this);
    }

    /**
     * return client info
     * @return ClientInfo
     */
    public function getClientInfo() {
        return $this->clientInfo;
    }

    /**
     * return payload
     * @return Payload
     */
    public function getPayload() {
        return $this->payload;
    }

}