<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CMultiTenantActiveRecord
 *
 * @author jonas
 */
abstract class CBaseActiveRecord extends CActiveRecord{
    public $sessionTenantAccountId;
}

?>
