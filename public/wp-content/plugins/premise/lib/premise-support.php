<?php

if(!class_exists('Premise_Support')) {
	class Premise_Support {
		function __construct() {
			$this->registerTests();
		}

		function sendSupportRequest($name, $email, $problem) {
			$to = $this->getEmailAddress();
			if( empty($to)) {
				return false;
			}

			$results = $this->getTestResults();
			$longest = 0;
			$output = "Greetings,\n\n";
			$output .= sprintf(__('This message is the result of running the %s support script on %s - you can access the site at %s .  The user was %s and you can reach them at the email address %s.'), __('Premise'), get_bloginfo('name'), home_url('/'), $name, $email) . "\n\n\n";
			$output .= "The user submitted the following message:\n\n{$problem}\n\n";
			foreach($results as $result) {
				$output .= "**{$result['name']}**\n{$result['value']}\n\n\n";
			}

			return wp_mail($to, sprintf(__('%s Support Message'), __('Premise')), $output, array('From: ' . $email));
		}

		function getKey($text) {
			$text = sanitize_title_with_dashes($text);
			return $text . '-' . sanitize_title_with_dashes($this->getOwnName());
		}

		function getOwnName() {
			return strtolower(get_class($this));
		}

		function getTestResults() {
			$results = array();
			foreach($this->_tests as $test) {
				$testResult = call_user_func($test['callback']);

				$results[] = array('name' => $test['name'], 'value' => $testResult['value'], 'warn' => $testResult['warn']);
			}
			return $results;
		}

		function registerTest($name, $callback) {
			if( is_callable($callback)) {
				$this->_tests[] = array('name' => $name, 'callback' => $callback);
			}
			return count($this->_tests);
		}

		function registerTests() {
			$this->registerTest(__('WordPress Version'), array(&$this, 'getWordPressVersion'));
			$this->registerTest(__('PHP Version'), array(&$this, 'getPHPVersion'));
			$this->registerTest(__('Premise Version'), array(&$this, 'getPremiseVersion'));
			$this->registerTest(__('Web Server'), array(&$this, 'getWebServer'));
			$this->registerTest(__('Server IP'), array(&$this, 'getIpAddress'));
			$this->registerTest(__('Installed Themes'), array(&$this, 'getInstalledThemes'));
			$this->registerTest(__('Activated Theme'), array(&$this, 'getActivatedTheme'));
			$this->registerTest(__('Installed Plugins'), array(&$this, 'getInstalledPlugins'));
			$this->registerTest(__('Activated Plugins'), array(&$this, 'getActivatedPlugins'));
		}

		function getEmailAddress() {
			return 'support@getpremise.com';
		}

		/// TEST CALLBACKS

		function getWordPressVersion() {
			global $wp_version;
			return array('value' => $wp_version, 'warn' =>  version_compare($wp_version, '2.8.4', '<'));
		}

		function getPHPVersion() {
			return array('value' =>  phpversion(), 'warn' => false);
		}

		function getWebServer() {
			$server = $_SERVER['SERVER_SOFTWARE'];
			if( empty($server)) {
				$server = __('Could not determine server software.');
			}
			return array('value' => $server, 'warn' => false);
		}

		function getIpAddress() {
			$ip = $_SERVER['SERVER_ADDR'];
			if(empty($ip)) {
				$ip = __('Could not determine server IP.');
			}
			return array('value' => $ip, 'warn' => false);
		}

		function isScribeInstalled() {
			global $ecordia;
			if( is_object($ecordia)) {
				$installed = __('Yes');
			} else {
				$installed = __('No');
			}
			return array('value' => $installed, 'warn' => ($installed == __('No')));
		}

		function getPremiseVersion() {
			global $Premise;
			if(is_object($Premise) && isset($Premise->_data_Version)) {
				$version = $Premise->_data_Version;
			} else {
				$version = __('Could not determine Premise version.');
			}

			return array('value' => $version, 'warn' => false);
		}

		function getInstalledThemes() {
			$themes = get_themes();
			$output = '';
			foreach($themes as $item) {
				$output .= "{$item['Name']}\n";
			}
			return array('value' => $output, 'warn' => false);
		}
		
		function getActivatedTheme() {
			$active = get_current_theme();
			
			return array('value' => $active, 'warn' => false);
		}

		function getInstalledPlugins() {
			$installed = get_plugins();
			$output = '';
			foreach($installed as $item) {
				$output .= "{$item['Name']}\n";
			}
			return array('value' => $output, 'warn' => false);
		}

		function getActivatedPlugins() {
			$active = get_option('active_plugins');
			$output = '';
			foreach($active as $item) {
				$path = path_join(WP_PLUGIN_DIR, $item);
				$data = get_plugin_data($path);
				$output .= "{$data['Name']}\n";
			}
			return array('value' => $output, 'warn' => false);
		}
	}
}
