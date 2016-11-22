<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\includes;

class p_master extends \p_master
{
	/**
	* Copied from phpBB 3.1.9 with one change:
	*
	*	Fix the style path for ACP modules which come within extensions
	*
	*	$template->set_custom_style(array(
	*		array(
	*			'name' 		=> 'adm',
	*			'ext_path' 	=> 'adm/style/',
	*		),
	*	), array($module_style_dir, $phpbb_admin_path . 'style'));
	*
	*	Change:
	*		$phpbb_admin_path . 'style'
	*	to:
	*		"styles/{$style}/style"
	*
	*	REMEMBER TO UPDATE CODE CHANGES FOR LATER PHPBB VERSIONS IF NEEDED
	*
	* @param string|false $mode mode, as passed through to the module
	* @param string|false $module_url If supplied, we use this module url
	* @param bool $execute_module If true, at the end we execute the main method for the new instance
	*/
	function load_active($mode = false, $module_url = false, $execute_module = true, $style = '')
	{
		global $phpbb_root_path, $phpbb_admin_path, $phpEx, $user, $template, $request;

		$module_path = $this->include_path . $this->p_class;
		$icat = $request->variable('icat', '');

		if ($this->active_module === false)
		{
			trigger_error('MODULE_NOT_ACCESS', E_USER_ERROR);
		}

		// new modules use the full class names, old ones are always called <type>_<name>, e.g. acp_board
		if (!class_exists($this->p_name))
		{
			if (!file_exists("$module_path/{$this->p_name}.$phpEx"))
			{
				trigger_error($user->lang('MODULE_NOT_FIND', "$module_path/{$this->p_name}.$phpEx"), E_USER_ERROR);
			}

			include("$module_path/{$this->p_name}.$phpEx");

			if (!class_exists($this->p_name))
			{
				trigger_error($user->lang('MODULE_FILE_INCORRECT_CLASS', "$module_path/{$this->p_name}.$phpEx", $this->p_name), E_USER_ERROR);
			}
		}

		if (!empty($mode))
		{
			$this->p_mode = $mode;
		}

		// Create a new instance of the desired module ...
		$class_name = $this->p_name;

		$this->module = new $class_name($this);

		// We pre-define the action parameter we are using all over the place
		if (defined('IN_ADMIN'))
		{
			/*
			* If this is an extension module, we'll try to automatically set
			* the style paths for the extension (the ext author can change them
			* if necessary).
			*/
			$module_dir = explode('\\', get_class($this->module));

			// 0 vendor, 1 extension name, ...
			if (isset($module_dir[1]))
			{
				$module_style_dir = $phpbb_root_path . 'ext/' . $module_dir[0] . '/' . $module_dir[1] . '/adm/style';

				if (is_dir($module_style_dir))
				{
					$template->set_custom_style(array(
						array(
							'name' 		=> 'adm',
							'ext_path' 	=> 'adm/style/',
						),
					), array($module_style_dir, "styles/{$style}/style"));
				}
			}

			// Is first module automatically enabled a duplicate and the category not passed yet?
			if (!$icat && $this->module_ary[$this->active_module_row_id]['is_duplicate'])
			{
				$icat = $this->module_ary[$this->active_module_row_id]['parent'];
			}

			// Not being able to overwrite ;)
			$this->module->u_action = append_sid("{$phpbb_admin_path}index.$phpEx", 'i=' . $this->get_module_identifier($this->p_name)) . (($icat) ? '&amp;icat=' . $icat : '') . "&amp;mode={$this->p_mode}";
		}
		else
		{
			/*
			* If this is an extension module, we'll try to automatically set
			* the style paths for the extension (the ext author can change them
			* if necessary).
			*/
			$module_dir = explode('\\', get_class($this->module));

			// 0 vendor, 1 extension name, ...
			if (isset($module_dir[1]))
			{
				$module_style_dir = 'ext/' . $module_dir[0] . '/' . $module_dir[1] . '/styles';

				if (is_dir($phpbb_root_path . $module_style_dir))
				{
					$template->set_style(array($module_style_dir, 'styles'));
				}
			}

			// If user specified the module url we will use it...
			if ($module_url !== false)
			{
				$this->module->u_action = $module_url;
			}
			else
			{
				$this->module->u_action = $phpbb_root_path . (($user->page['page_dir']) ? $user->page['page_dir'] . '/' : '') . $user->page['page_name'];
			}

			$this->module->u_action = append_sid($this->module->u_action, 'i=' . $this->get_module_identifier($this->p_name)) . (($icat) ? '&amp;icat=' . $icat : '') . "&amp;mode={$this->p_mode}";
		}

		// Add url_extra parameter to u_action url
		if (!empty($this->module_ary) && $this->active_module !== false && $this->module_ary[$this->active_module_row_id]['url_extra'])
		{
			$this->module->u_action .= $this->module_ary[$this->active_module_row_id]['url_extra'];
		}

		// Assign the module path for re-usage
		$this->module->module_path = $module_path . '/';

		// Execute the main method for the new instance, we send the module id and mode as parameters
		// Users are able to call the main method after this function to be able to assign additional parameters manually
		if ($execute_module)
		{
			$short_name = preg_replace("#^{$this->p_class}_#", '', $this->p_name);
			$this->module->main($short_name, $this->p_mode);
		}
	}
}
