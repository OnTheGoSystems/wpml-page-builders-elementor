<?php

class WPML_PB_Fix_Maintenance_Query_Factory implements IWPML_Frontend_Action_Loader {

	public function create() {
		return new WPML_PB_Fix_Maintenance_Query();
	}
}