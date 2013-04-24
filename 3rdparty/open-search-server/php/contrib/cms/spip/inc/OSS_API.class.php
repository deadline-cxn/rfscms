<?php
/*
 *  This file is part of OpenSearchServer.
 *
 *  Copyright (C) 2008-2009 Emmanuel Keller / Jaeksoft
 *
 *  http://www.open-search-server.com
 *
 *  OpenSearchServer is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  OpenSearchServer is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with OpenSearchServer.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!extension_loaded('curl')) { trigger_error("OSS_API won't work whitout curl extension", E_USER_ERROR); die(); }
if (!extension_loaded('SimpleXML')) { trigger_error("OSS_API won't work whitout SimpleXML extension", E_USER_ERROR); die(); }

if (!class_exists('RuntimeException')) { class RuntimeException extends Exception {} }
if (!class_exists('LogicException')) { class LogicException extends Exception {} }
if (!class_exists('InvalidArgumentException')) { class InvalidArgumentException extends LogicException {} }
if (!class_exists('OutOfRangeException')) { class OutOfRangeException extends LogicException {} }

/**
 * Class to access OpenSearchServer API
 * @author pmercier <pmercier@open-search-server.com>
 * @package OpenSearchServer
 */
class OSS_API {

	const VERSION = '1.2b';
	
	const API_SELECT   = 'select';
	const API_UPDATE   = 'update';
	const API_DELETE   = 'delete';
	const API_OPTIMIZE = 'optimize';
	const API_RELOAD   = 'reload';
	const API_INDEX    = 'index';
	const API_ENGINE   = 'engine';

	/** @var int Default timeout (specified in seconds) for CURLOPT_TIMEOUT option. See curl documentation */
	const DEFAULT_QUERY_TIMEOUT = 0;

	/** @var int Timeout (specified in seconds) for CURLOPT_CONNECTTIMEOUT option. See curl documentation */
	const DEFAULT_CONNEXION_TIMEOUT = 5;

	/**
	 * List of supported languages
	 * @todo Provide an API to request the supported languages from the engine (if possible)
	 * @var array
	 */
	protected static $supportedLanguages = array(
			""   => "Undefined",
			"zh" => "Chinese",
			"da" => "Danish",
			"nl" => "Dutch",
			"en" => "English",
			"fi" => "Finnish",
			"fr" => "French",
			"de" => "German",
			"hu" => "Hungarian",
			"it" => "Italian",
			"no" => "Norwegian",
			"pt" => "Portuguese",
			"ro" => "Romanian",
			"ru" => "Russian",
			"es" => "Spanish",
			"sv" => "Swedish",
			"tr" => "Turkish"
	);

	/* @var string */
	protected $enginePath;
	
	/* @var string */
	protected $index;
	
	/**
	 * @param $enginePath The URL to access the OSS Engine
	 * @param $index The index name
	 * @return OSS_API
	 */
	public function __construct($enginePath, $index = null) {
		
		$parsedPath = OSS_API::parseEnginePath($enginePath, $index);
		$this->enginePath	= $parsedPath['enginePath'];
		$this->index		= $parsedPath['index'];
		
		if (!function_exists('OSS_API_Dummy_Function')) { function OSS_API_Dummy_Function() {} }
	}

	/**
	 * Return an OSS_Search using the current engine path and index
	 * @return OSS_Search
	 * This method require the file OSS_Search.class.php. It'll be included if the OSS_Search class don't exist.
	 * It's expected to be in the same directory as OSS_API.class.php.
	 */
	public function select() {
		if (!class_exists('OSS_Search')) require (dirname(__FILE__).'/OSS_Search.class.php');
		return new OSS_Search($this->enginePath, $this->index);
	}

	/**
	 * Return an OSS_Search using the current engine path and index
	 * @return OSS_Search
	 * @deprecated Use OSS_API::select
	 */
	public function search() {
		return $this->select();
	}

	/**
	 * Optimize the index
	 * @return boolean True on success
	 * @see OSS Wiki [Web API optimize] documentation before using this method
	 * FIXME Provide a link to the OSS WiKi
	 */
	public function optimize() {
		$return = $this->queryServer($this->getQueryURL(OSS_API::API_OPTIMIZE));
		return ($return !== false);
	}

	/**
	 * Reload the index
	 * @return boolean True on success
	 * @see OSS Wiki [Web API reload] documentation before using this method
	 * FIXME Provide a link to the OSS WiKi
	 */
	public function reload() {
		$return = $this->queryServer($this->getQueryURL(OSS_API::API_RELOAD));
		return ($return !== false);
	}

	/**
	 * Not implemented yet
	 * @todo Next release
	 * @ignore
	 * @param array<string> $ids
	 * @return null
	 * FIXME See with ekeller if this's api won't be deprecated soon
	 */
	public function delete($ids) {
		// http://localhost:8080/oss/delete?use=spip_index&uniq=article_2
	}
	
	/**
	 * @todo Next release
	 * @ignore
	 * @param string $file
	 * @return null
	 * FIXME See with ekeller if this's api won't be deprecated soon
	 */
	public function push($file) {
		// http://localhost:8080/oss/push?use=fileNiet&fileName=test.pdf&version=1
	}
	
	/**
	 * Return the url to use with curl
	 * @param string $apiCall The Web API to call. Refer to the OSS Wiki documentation of [Web API]
	 * @return string
	 * Use OSS_API::API_* constants for $apiCall
	 */
	protected function getQueryURL($apiCall) {
		$path = $this->enginePath.'/'.$apiCall;
		if (!empty($this->index)) $path .= '?use='.$this->index;
		return $path;
	}

	/**
	 * Send an xml list of documents to be indexed by the search engine
	 * @param mixed $xml Can be an xml string, a OSS_IndexDocument, a SimpleXMLElement,
	 *                   a DOMDocument or any object that implement the __toString
	 *                   magic method
	 * @return boolean True on success
	 */
	public function update($xml) {

		// Cast $xml to a string
		if (!is_string($xml)) {
			if ($xml instanceof DOMDocument) {
				$xml = $xml->saveXML();
			}
			elseif ($xml instanceof SimpleXMLElement) {
				$xml = $xml->asXML();
			}
			elseif (is_object($xml)) {
				if (method_exists($xml, '__toString') || $xml instanceof SimpleXMLElement) {
					$xml = $xml->__toString();
				}
			}
		}

		if (!is_string($xml)) {
			if (class_exists('OSSException'))
				throw new UnexpectedValueException('String, SimpleXMLElement or DOMDocument was expected for $xml.');
			trigger_error(__CLASS__.'::'.__METHOD__.'($xml): String, SimpleXMLElement or DOMDocument was expected for $xml.', E_USER_ERROR);
			return false;
		}

		$return = $this->queryServer($this->getQueryURL(OSS_API::API_UPDATE), $xml);
		return ($return !== false);

	}


	/**
	 * Return informations about the OSS Engine
	 * @todo Finish implementation once API is availabe
	 * @return array
	 */
	public function getEngineInformations() {
		$infos = array(
			'engine_url'     => $this->enginePath,
			'engine_version' => 'unknown',
			'user'           => '',
			'password'       => ''
		);
		return $infos;
		//return OSS_API::queryServerXML($this->enginePath.'/'.OSS_API::API_ENGINE);
	}
	
	/**
	 * Return informations about the index
	 * @todo Finish implementation once API is availabe
	 * @return array
	 */
	public function getIndexInformations() {
		
		$infos  = array(
			'name'  => $this->index,
			'size'  => null
		);
		
		set_error_handler('OSS_API_Dummy_Function', E_ALL);
		try { $result = $this->queryServerXML($this->getQueryURL(OSS_API::API_SELECT).'&q=*:*&rows=0'); }
		catch (Exception $e) { $result = false; }
		restore_error_handler();
		if ($result instanceof SimpleXMLElement) {
			$infos['count'] = $result->result['numFound']; 
		}
		
		return $infos;
		//return $this->queryServerXML($this->getQueryURL(OSS_API::API_INDEX));
	}
	
	/**
	 * Check if the engine is running. Don't check the existance of the index.
	 * @return boolean Return null if can't connect to tomcat. Return false if engine fail to answer. Return true if the engine is running.
	 * @todo Recode this method once API is provided
	 */
	public function isEngineRunning() {
		
		// Check if the select api is answering
		$rCurl = curl_init($this->getQueryURL(OSS_API::API_SELECT).'&q=!*:*&rows=0');
		curl_setopt($rCurl, CURLOPT_HTTP_VERSION, '1.0');
		curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($rCurl, CURLOPT_CONNECTTIMEOUT, 5);
		set_error_handler('OSS_API_Dummy_Function', E_ALL);
		curl_exec($rCurl);
		restore_error_handler();
		$infos = curl_getinfo($rCurl);
		if ($infos['http_code'] >= 200 && $infos['http_code'] < 300) return true;
		if ($infos['http_code'] == 0) return null;
		return false;
		
	}
	
	/**
	 * Check if the index is available
	 * @return boolean True if exist.
	 * @todo Recode this method once API is provided 
	 */
	public function isIndexAvailable() {
		// Check if the select api is answering
		set_error_handler('OSS_API_Dummy_Function', E_ALL);
		try { $result = $this->queryServerXML($this->getQueryURL(OSS_API::API_SELECT).'&q=!*:*&rows=0'); }
		catch (Exception $e) { $result = false; }
		restore_error_handler();
		return (bool)$result;
	}
	
	
	/**
	 * Post data to an URL
	 * @param string $url
	 * @param string $data Optional. If provided will use a POST method. Only accept
	 *                     data as POST encoded string or raw XML string.
	 * @param int $timeout Optional. Number of seconds before the query fail
	 * @return false|string
	 *
	 * Will fail if more than 16 HTTP redirection
	 */
	public static function queryServer($url, $data = null, $connexionTimeout = OSS_API::DEFAULT_CONNEXION_TIMEOUT, $timeout = OSS_API::DEFAULT_QUERY_TIMEOUT) {

		// Use CURL to post the data
		$rCurl = curl_init($url);
		curl_setopt($rCurl, CURLOPT_HTTP_VERSION, '1.0');
		curl_setopt($rCurl, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($rCurl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($rCurl, CURLOPT_MAXREDIRS, 16);
		curl_setopt($rCurl, CURLOPT_VERBOSE, true);

		if (is_integer($connexionTimeout) && $connexionTimeout >= 0)
			curl_setopt($rCurl, CURLOPT_CONNECTTIMEOUT, $connexionTimeout);

		if (is_integer($timeout) && $timeout >= 0)
			curl_setopt($rCurl, CURLOPT_TIMEOUT, $timeout);

		// Send provided string as POST data. Must be encoded to meet POST specification
		if ($data !== null) {
			curl_setopt($rCurl, CURLOPT_POST, true);
			curl_setopt($rCurl, CURLOPT_POSTFIELDS, (string)$data);
			curl_setopt($rCurl, CURLOPT_HTTPHEADER, array("Content-type: text/xml; charset=utf-8"));
		}

		set_error_handler('OSS_API_Dummy_Function', E_ALL);
		$content = curl_exec($rCurl);
		restore_error_handler();
		
		if ($content === false) {
			if (class_exists('OSSException'))
				throw new RuntimeException('CURL failed to execute on URL "'.$url.'"');
			trigger_error('CURL failed to execute on URL "'.$url.'"', E_USER_WARNING);
			return false;
		}

		$aResponse 	= curl_getinfo($rCurl);

		// Must check return code
		if ($aResponse['http_code'] >= 400) {
			if (class_exists('OSSException'))
				throw new TomcatException($aResponse['http_code'], $content);
			trigger_error('HTTP ERROR '.$aResponse['http_code'].': "'.trim(strip_tags($content)).'"', E_USER_WARNING);
			return false;
		}

		// FIXME Possible problem to identify Locked Index message. Must set a lock on an index to check this
		if (OSS_API::isOSSError($content)) {
			if (class_exists('OSSException'))
				throw new OSSException($content);
			trigger_error('OSS Returned an error: "'.trim(strip_tags($content)).'"', E_USER_WARNING);
			return false;
		}
		
		return $content;
	}

	/**
	 * Post data to an URL and retrieve an XML
	 * @param string $url
	 * @param string $data Optional. If provided will use a POST method. Only accept
	 *                     data as POST encoded string or raw XML string.
	 * @param int $timeout Optional. Number of seconds before the query fail
	 * @return SimpleXMLElement
	 * Use OSS_API::queryServerto retrieve an XML and check its validity
	 */
	public function queryServerXML($url, $data = null, $connexionTimeout = OSS_API::DEFAULT_CONNEXION_TIMEOUT, $timeout = OSS_API::DEFAULT_QUERY_TIMEOUT) {
		$result = OSS_API::queryServer($url, $data, $connexionTimeout, $timeout);
		if ($result === false) return false;
		
		// Check if we have a valid XML string from the engine
		$lastErrorLevel = error_reporting(0);
		$xmlResult = simplexml_load_string(OSS_API::cleanUTF8($result));
		error_reporting($lastErrorLevel);
		if (!$xmlResult instanceof SimpleXMLElement) {
			if (class_exists('OSSException'))
				throw new RuntimeException("The search engine didn't return a valid XML");
			trigger_error("The search engine didn't return a valid XML", E_USER_WARNING);
			return false;
		}

		return $xmlResult;
	}
	
	/**
	 * Check if the answer is an error returned by OSS
	 * @param $xml string, DOMDocument or SimpleXMLElement
	 * @return boolean True if error success
	 */
	public static function isOSSError($xml) {

		// Cast $xml param to be a SimpleXMLElement
		// If we don't find the word 'Error' in the xml string, exit immediatly
		if ($xml instanceof SimpleXMLElement) {
			if (strpos((string)$xml, 'Error') === false)
				return false;
			$xmlDoc = $xml;
		}
		elseif ($xml instanceof DOMDocument) {
			$xmlDoc = simplexml_import_dom($xml);
			if (strpos((string)$xmlDoc, 'Error') === false)
				return false;
		}
		else {
			if (strpos((string)$xml, 'Error') === false)
				return false;
			$previousErrorlevel = error_reporting(0);
			$xmlDoc = simplexml_load_string($xml);
			error_reporting($previousErrorlevel);
		}

		if (!$xmlDoc instanceof SimpleXMLElement) return false;

		// Make sure the Error we found was a Status Error
		foreach ($xmlDoc->entry as $entry) {
			if ($entry['key'] == 'Status' && $entry == 'Error') {
				return true;
			}
		}

		return false;
	}

	/**
	 * Parse the enginePath parameter to extract the index name
	 * @param $enginePath The URL to access the OSS Engine
	 * @param $index The index name
	 */
	public static function parseEnginePath($enginePath, $index = null) {
		
		$urlParams = array();
		// Extract the use param in the query part if any
		if (strpos($enginePath, '?') !== false) {
			$parsedURL = parse_url($enginePath);
			parse_str($parsedURL['query'], $urlParams);
			if (isset($urlParams['use'])) {
				$index = $urlParams['use'];
				$enginePath = str_replace('&&', '&', str_replace("use=".$urlParams['use'], '', $enginePath));
				if (substr($enginePath, -1) == '?')
					$enginePath = substr($enginePath, 0, -1);
			}
		}
		
		return array('enginePath' => $enginePath, 'index' => $index);
		
	}
	
	/**
	 * Return a list of supported language. Array is indexed by ISO 639-1 format (en, de, fr, ...)
	 * @return Array<String>
	 * @see OSS_API::$supportedLanguages
	 */
	public static function supportedLanguages() {
		return OSS_API::$supportedLanguages;
	}

	/**
	 * Escape Lucene's special chars
	 * @param string $string
	 * @return string
	 */
	public static function escape($string) {
		static $escaping = array(
			array("+",   "-",   "&&",   "||",  "!",  "(",  ")",  "{",  "}",  "[",  "]",  "^", "\"",  "~",  "*",  "?",  ":", '\\'),
			array('\+', '\-', '\&\&', '\|\|', '\!', '\(', '\)', '\{', '\}', '\[', '\]', '\^', '\"', '\~', '\*', '\?', '\:', '\\\\')
		);
		return str_replace($escaping[0], $escaping[1], $string);
	}
	
	/**
	 * Clean an UTF-8 string to prevent simpleXMLElement to fail on some characters (remove them)
	 * @param string $string
	 * @return string
	 */
	public static function cleanUTF8($string, $replacement = '') {

		static $remove = array(	"\x00", "\x01", "\x02", "\x03", "\x04", "\x05", "\x06", "\x07",
								"\x08",                 "\x0B", "\x0C",         "0x0E", "\x0F",
								"\x10", "\x11", "\x12", "\x13", "\x14", "\x15", "\x16", "\x17",
								"\x18", "\x19", "\x1A", "\x1B", "\x1C", "\x1D", "\x1E", "\x1F"
		);
		return str_replace($remove, $replacement, $string);
		
	}
	
}