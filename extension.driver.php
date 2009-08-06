<?php
	
	class Extension_TextBoxField extends Extension {
	/*-------------------------------------------------------------------------
		Definition:
	-------------------------------------------------------------------------*/
		
		protected static $fields = array();
		
		public function about() {
			return array(
				'name'			=> 'Field: Text Box',
				'version'		=> '2.0.8',
				'release-date'	=> '2009-08-06',
				'author'		=> array(
					'name'			=> 'Rowan Lewis',
					'website'		=> 'http://rowanlewis.com/',
					'email'			=> 'me@rowanlewis.com'
				),
				'description' => 'An enhanced text input field.'
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
		
	/*-------------------------------------------------------------------------
		Utilites:
	-------------------------------------------------------------------------*/
		
		protected $addedHeaders = false;
		protected $addedFilteringHeaders = false;
		
		public function addHeaders($page) {
			if ($page and !$this->addedHeaders) {
				$page->addStylesheetToHead(URL . '/extensions/textboxfield/assets/publish.css', 'screen', 10251840);
				
				$this->addedHeaders = true;
			}
		}
		
		public function addFilteringHeaders($page) {
			if ($page and !$this->addedFilteringHeaders) {
				$page->addScriptToHead(URL . '/extensions/textboxfield/assets/interface.js', 10251840);
				$page->addScriptToHead(URL . '/extensions/textboxfield/assets/filtering.js', 10251841);
				$page->addStylesheetToHead(URL . '/extensions/textboxfield/assets/filtering.css', 'screen', 10251840);
				
				$this->addedFilteringHeaders = true;
			}
		}
	}
	
?>
