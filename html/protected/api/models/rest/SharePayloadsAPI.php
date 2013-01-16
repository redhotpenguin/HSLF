<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SharePaylodsAPI
 *
 * @author jonas
 */
class SharePayloadsAPI  extends APIBase {

    public function __construct() {
        parent::__construct(new SharePayload);
    }
}

