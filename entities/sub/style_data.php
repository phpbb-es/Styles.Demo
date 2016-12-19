<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\entities\sub;

use vinabb\stylesdemo\includes\constants;

/**
* Sub-entity for style_data
*/
class style_data
{
	/** @var array $data */
	protected $data;

	/**
	* Get the style version
	*
	* @return string
	*/
	public function get_version()
	{
		return isset($this->data['style_version']) ? (string) $this->data['style_version'] : '';
	}

	/**
	* Set the style version
	*
	* @param string					$text	Style version
	* @return acp_style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_version($text)
	{
		$text = (string) $text;

		// This is a required field
		if ($text == '')
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_version', 'EMPTY']);
		}

		// Check the max length
		if (utf8_strlen($text) > 20)
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_version', 'TOO_LONG']);
		}

		// Set the value on our data array
		$this->data['style_version'] = $text;

		return $this;
	}

	/**
	* Get the phpBB version
	*
	* @return string
	*/
	public function get_phpbb_version()
	{
		return isset($this->data['style_phpbb_version']) ? (string) $this->data['style_phpbb_version'] : '';
	}

	/**
	* Set the phpBB version
	*
	* @param string					$text	phpBB version
	* @return acp_style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_phpbb_version($text)
	{
		$text = (string) $text;

		// This is a required field
		if ($text == '')
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_phpbb_version', 'EMPTY']);
		}

		// Check valid version number
		if (!preg_match('#^\d+(\.\d){1,3}(\-(((?:a|b|RC|PL)\d+)|dev))?$#', $text))
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_phpbb_version', 'INVALID']);
		}

		// Check the max length
		if (utf8_strlen($text) > 20)
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_phpbb_version', 'TOO_LONG']);
		}

		// Set the value on our data array
		$this->data['style_phpbb_version'] = $text;

		return $this;
	}

	/**
	* Get the author name
	*
	* @return string
	*/
	public function get_author()
	{
		return isset($this->data['style_author']) ? (string) $this->data['style_author'] : '';
	}

	/**
	* Set the author name
	*
	* @param string					$text	Author name
	* @return acp_style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_author($text)
	{
		$text = (string) $text;

		// Check the max length
		if (utf8_strlen($text) > constants::MAX_CONFIG_NAME)
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_author', 'TOO_LONG']);
		}

		// Set the value on our data array
		$this->data['style_author'] = $text;

		return $this;
	}

	/**
	* Get the author website
	*
	* @return string
	*/
	public function get_author_url()
	{
		return isset($this->data['style_author_url']) ? (string) $this->data['style_author_url'] : '';
	}

	/**
	* Set the author website
	*
	* @param string					$text	Author website URL
	* @return acp_style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_author_url($text)
	{
		$text = (string) $text;

		// Check the valid URL
		if ($text != '' && filter_var($text, FILTER_VALIDATE_URL) === false)
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_author_url', 'INVALID_URL']);
		}

		// Check the max length
		if (utf8_strlen($text) > constants::MAX_CONFIG_NAME)
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_author_url', 'TOO_LONG']);
		}

		// Set the value on our data array
		$this->data['style_author_url'] = $text;

		return $this;
	}

	/**
	* Get the number of style presets
	*
	* @return int
	*/
	public function get_presets()
	{
		return isset($this->data['style_presets']) ? (int) $this->data['style_presets'] : 0;
	}

	/**
	* The style is responsive?
	*
	* @return bool
	*/
	public function get_responsive()
	{
		return isset($this->data['style_responsive']) ? (bool) $this->data['style_responsive'] : false;
	}

	/**
	* Get the style price
	*
	* @return int
	*/
	public function get_price()
	{
		return isset($this->data['style_price']) ? (int) $this->data['style_price'] : 0;
	}

	/**
	* Get the style price with currency
	*
	* @return string
	*/
	public function get_price_label()
	{
		return isset($this->data['style_price_label']) ? (string) $this->data['style_price_label'] : '';
	}

	/**
	* Set the style price with currency
	*
	* @param string				$text	Style price with currency
	* @return acp_style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_price_label($text)
	{
		$text = (string) $text;

		// Check the max length
		if (utf8_strlen($text) > constants::MAX_CONFIG_NAME)
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_price_label', 'TOO_LONG']);
		}

		// Set the value on our data array
		$this->data['style_price_label'] = $text;

		return $this;
	}

	/**
	* Get the style's download URL
	*
	* @return string
	*/
	public function get_download()
	{
		return isset($this->data['style_download']) ? (string) htmlspecialchars_decode($this->data['style_download']) : '';
	}

	/**
	* Set the style's download URL
	*
	* @param string					$text	Download URL
	* @return acp_style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_download($text)
	{
		$text = (string) $text;

		// Check the valid URL
		if ($text != '' && filter_var($text, FILTER_VALIDATE_URL) === false)
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_download', 'INVALID_URL']);
		}

		// Check the max length
		if (utf8_strlen($text) > constants::MAX_CONFIG_NAME)
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_download', 'TOO_LONG']);
		}

		// Set the value on our data array
		$this->data['style_download'] = $text;

		return $this;
	}

	/**
	* Get the style's mirror URLs
	*
	* @return string
	*/
	public function get_mirror()
	{
		return isset($this->data['style_mirror']) ? (string) $this->data['style_mirror'] : '';
	}

	/**
	* Get the style's details URL
	*
	* @return string
	*/
	public function get_details()
	{
		return isset($this->data['style_details']) ? (string) $this->data['style_details'] : '';
	}

	/**
	* Set the style's details URL
	*
	* @param string					$text	Details URL
	* @return acp_style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_details($text)
	{
		$text = (string) $text;

		// Check the valid URL
		if ($text != '' && filter_var($text, FILTER_VALIDATE_URL) === false)
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_details', 'INVALID_URL']);
		}

		// Check the max length
		if (utf8_strlen($text) > constants::MAX_CONFIG_NAME)
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_details', 'TOO_LONG']);
		}

		// Set the value on our data array
		$this->data['style_details'] = $text;

		return $this;
	}

	/**
	* Get the style's support URL
	*
	* @return string
	*/
	public function get_support()
	{
		return isset($this->data['style_support']) ? (string) $this->data['style_support'] : '';
	}

	/**
	* Set the style's support URL
	*
	* @param string					$text	Support URL
	* @return acp_style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_support($text)
	{
		$text = (string) $text;

		// Check the valid URL
		if ($text != '' && filter_var($text, FILTER_VALIDATE_URL) === false)
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_support', 'INVALID_URL']);
		}

		// Check the max length
		if (utf8_strlen($text) > constants::MAX_CONFIG_NAME)
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_support', 'TOO_LONG']);
		}

		// Set the value on our data array
		$this->data['style_support'] = $text;

		return $this;
	}
}
