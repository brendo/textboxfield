<?php
	
	class Extension_TextBoxField extends Extension {
		public function about() {
			return array(
				'name'			=> 'Field: Text Box',
				'version'		=> '2.1',
				'release-date'	=> '2010-11-01',
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
					`column_length` INT(11) UNSIGNED DEFAULT 75,
					`text_size` ENUM('single', 'small', 'medium', 'large', 'huge') DEFAULT 'medium',
					`text_formatter` VARCHAR(255) DEFAULT NULL,
					`text_validator` VARCHAR(255) DEFAULT NULL,
					`text_length` INT(11) UNSIGNED DEFAULT 0,
					`text_cdata` ENUM('yes', 'no') DEFAULT 'no',
					`text_handle` ENUM('yes', 'no') DEFAULT 'no',
					PRIMARY KEY (`id`),
					KEY `field_id` (`field_id`)
				)
			");
			
			return true;
		}
		
		public function update($previousVersion) {
			// Column length:
			if ($this->updateHasColumn('show_full')) {
				$this->updateRemoveColumn('show_full');
			}
			
			if (!$this->updateHasColumn('column_length')) {
				$this->updateAddColumn('column_length', 'INT(11) UNSIGNED DEFAULT 75 AFTER `field_id`');
			}
			
			// Text size:
			if ($this->updateHasColumn('size')) {
				$this->updateRenameColumn('size', 'text_size');
			}
			
			// Text formatter:
			if ($this->updateHasColumn('formatter')) {
				$this->updateRenameColumn('formatter', 'text_formatter');
			}
			
			// Text validator:
			if ($this->updateHasColumn('validator')) {
				$this->updateRenameColumn('validator', 'text_validator');
			}
			
			// Text length:
			if ($this->updateHasColumn('length')) {
				$this->updateRenameColumn('length', 'text_length');
			}
			
			else if (!$this->updateHasColumn('text_length')) {
				$this->updateAddColumn('text_length', 'INT(11) UNSIGNED DEFAULT 0 AFTER `text_formatter`');
			}
			
			// Text CDATA:
			if (!$this->updateHasColumn('text_cdata')) {
				$this->updateAddColumn('text_cdata', "ENUM('yes', 'no') DEFAULT 'no' AFTER `text_length`");
			}
			
			// Text handle:
			if (!$this->updateHasColumn('text_handle')) {
				$this->updateAddColumn('text_handle', "ENUM('yes', 'no') DEFAULT 'no' AFTER `text_cdata`");
			}
			
			return true;
		}
		
		public function updateAddColumn($column, $type) {
			return Symphony::Database()->query("
				ALTER TABLE
					`tbl_fields_textbox`
				ADD COLUMN
					`{$column}` {$type}
			");
		}
		
		public function updateHasColumn($column) {
			return (boolean)Symphony::Database()->fetchVar(
				'Field', 0,
				"
					SHOW COLUMNS FROM
						`tbl_fields_textbox`
					WHERE
						Field = '{$column}'
				"
			);
		}
		
		public function updateRemoveColumn($column) {
			return Symphony::Database()->query("
				ALTER TABLE
					`tbl_fields_textbox`
				DROP COLUMN
					`{$column}`
			");
		}
		
		public function updateRenameColumn($from, $to) {
			header('content-type: text/html');
			
			$data = Symphony::Database()->fetchRow(
				0, "
					SHOW COLUMNS FROM
						`tbl_fields_textbox`
					WHERE
						Field = '{$from}'
				"
			);
			
			if (!is_null($data['Default'])) {
				$type = 'DEFAULT ' . var_export($data['Default'], true);
			}
			
			else if ($data['Null'] == 'YES') {
				$type .= 'DEFAULT NULL';
			}
			
			else {
				$type .= 'NOT NULL';
			}
			
			return Symphony::Database()->query(sprintf(
				"
					ALTER TABLE
						`tbl_fields_textbox`
					CHANGE
						`%s` `%s` %s
				",
				$from, $to,
				$data['Type'] . ' ' . $type
			));
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
