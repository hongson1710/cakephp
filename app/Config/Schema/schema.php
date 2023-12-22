<?php
/* App schema generated on: 2023-12-22 07:35:46 : 1703226946*/
class AppSchema extends CakeSchema {

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

    public $acos = array(
            'id' => array(
                    'type' => 'integer',
                    'null' => false,
                    'default' => null,
                    'length' => 10,
                    'key' => 'primary'
            ),
            'contact_name' => array(
                    'type' => 'string',
                    'null' => true,
                    'default' => null
            ),
    );
}
?>
