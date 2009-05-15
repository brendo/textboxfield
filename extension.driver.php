<?php
	
	class Extension_TextBoxField extends Extension {
	/*-------------------------------------------------------------------------
		Definition:
	-------------------------------------------------------------------------*/
		
		protected static $fields = array();
		
		public function about() {
			return array(
				'name'			=> 'Field: Text Box',
				'version'		=> '2.0.3',
				'release-date'	=> '2009-05-15',
				'author'		=> array(
					'name'			=> 'Rowan Lewis',
					'website'		=> 'http://pixelcarnage.com/',
					'email'			=> 'rowan@pixelcarnage.com'
				),
				'description' => '
					An enhanced text input field.
				'
			);
		}
		
		public function uninstall() {
			$this->_Parent->Database->query("DROP TABLE `tbl_fields_textbox`");
		}
		
		public function install() {
			$this->_Parent->Database->query("
				CREATE TABLE IF NOT EXISTS `tbl_fields_textbox` (
					`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					`field_id` INT(11) UNSIGNED NOT NULL,
					`formatter` VARCHAR(255) DEFAULT NULL,
					`size` ENUM('single', 'small', 'medium', 'large', 'huge') DEFAULT 'medium',
					`validator` VARCHAR(255) DEFAULT NULL,
					PRIMARY KEY (`id`),
					KEY `field_id` (`field_id`)
				)
			");
			
			return true;
		}
	}
	
?>
