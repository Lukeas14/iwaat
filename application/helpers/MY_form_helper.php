<?php

/**
 * Form Declaration
 *
 * Creates the opening portion of the form.
 *
 * @access	public
 * @param	string	the URI segments of the form destination
 * @param	array	a key/value pair of attributes
 * @param	array	a key/value pair hidden data
 * @return	string
 */
function form_open($action = '', $attributes = '', $hidden = array())
{
	$CI =& get_instance();

	if ($attributes == '')
	{
		$attributes = 'method="post"';
	}

	// If an action is not a full URL then turn it into one
	if ($action && strpos($action, '://') === FALSE)
	{
		$action = $CI->config->site_url($action);
	}

	// If no action is provided then set to the current url
	$action OR $action = $CI->config->site_url($CI->uri->uri_string());

	$form = '<form action="'.$action.'"';

	$form .= _attributes_to_string($attributes, TRUE);

	$form .= '>';

	// CSRF
	if ($CI->config->item('csrf_protection') === TRUE)
	{
		$hidden[$CI->security->get_csrf_token_name()] = $CI->security->get_csrf_hash();
	}

	if (is_array($hidden) AND count($hidden) > 0)
	{
		$form .= sprintf("\n<div class=\"hidden\">%s</div>", form_hidden($hidden));
	}

	return $form;
}
