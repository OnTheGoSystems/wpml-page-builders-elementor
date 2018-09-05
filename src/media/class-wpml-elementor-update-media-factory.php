<?php

class WPML_Elementor_Update_Media_Factory implements IWPML_PB_Media_Update_Factory {

	public function create() {
		global $sitepress;

		return new WPML_Page_Builders_Update_Media(
			new WPML_Page_Builders_Update( new WPML_Elementor_Data_Settings() ),
			new WPML_Translation_Element_Factory( $sitepress ),
			new WPML_Elementor_Media_Nodes_Iterator( new WPML_Elementor_Media_Node_Provider() )
		);
	}
}
