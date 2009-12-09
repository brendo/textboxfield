<?php
	
	class Extension_TextBoxField extends Extension {
	/*-------------------------------------------------------------------------
		Definition:
	-------------------------------------------------------------------------*/
		
		protected static $fields = array();
		
		public function about() {
			return array(
				'name'			=> 'Field: Text Box',
				'version'		=> '2.0.11',
				'release-date'	=> '2009-12-09',
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
					`length` INT(11) UNSIGNED DEFAULT NULL,
					PRIMARY KEY (`id`),
					KEY `field_id` (`field_id`)
				)
			");
			
			return true;
		}
		
		public function update($previousVersion) {
			$has_length_column = (boolean)$this->_Parent->Database->fetchVar(
				'Field', 0,
				"
					SHOW COLUMNS FROM
						`tbl_fields_textbox`
					WHERE
						Field = 'length'
				"
			);
			
			if (!$has_length_column) {
				$this->_Parent->Database->query("
					ALTER TABLE
						`tbl_fields_textbox`
					ADD COLUMN
						`length` INT(11) UNSIGNED DEFAULT NULL
					AFTER
						`size`
				");
			}
			
			return true;
		}
		
	/*-------------------------------------------------------------------------
		Utilites:
	-------------------------------------------------------------------------*/
		
		protected $addedPublishHeaders = false;
		protected $addedSettingsHeaders = false;
		protected $addedFilteringHeaders = false;
		
		public function addPublishHeaders($page) {
			if ($page and !$this->addedPublishHeaders) {
				$page->addStylesheetToHead(URL . '/extensions/textboxfield/assets/publish.css', 'screen', 10251840);
				$page->addScriptToHead(URL . '/extensions/textboxfield/assets/publish.js', 10251840);
				
				$this->addedPublishHeaders = true;
			}
		}
		
		public function addSettingsHeaders($page) {
			if ($page and !$this->addedSettingsHeaders) {
				$page->addStylesheetToHead(URL . '/extensions/textboxfield/assets/settings.css', 'screen', 10251840);
				
				$this->addedSettingsHeaders = true;
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
