<?php
/**
*
* This file is part of the phpBB Forum Software package.
*
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
*/

namespace phpbb\auth\provider;

/**
* Sandstorm authentication provider for phpBB3
*/
class sandstorm extends \phpbb\auth\provider\base
{
	/**
	* phpBB passwords manager
	*
	* @var \phpbb\passwords\manager
	*/
	protected $passwords_manager;

	/**
	 * Sandstorm Authentication Constructor
	 *
	 * @param	\phpbb\db\driver\driver_interface 	$db		Database object
	 * @param	\phpbb\config\config 		$config		Config object
	 * @param	\phpbb\passwords\manager	$passwords_manager		Passwords Manager object
	 * @param	\phpbb\request\request 		$request		Request object
	 * @param	\phpbb\user 			$user		User object
	 * @param	string 				$phpbb_root_path		Relative path to phpBB root
	 * @param	string 				$php_ext		PHP file extension
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\config\config $config, \phpbb\passwords\manager $passwords_manager, \phpbb\request\request $request, \phpbb\user $user, $phpbb_root_path, $php_ext)
	{
		$this->db = $db;
		$this->config = $config;
		$this->passwords_manager = $passwords_manager;
		$this->request = $request;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		if (!$this->request->is_set('PHP_AUTH_USER', \phpbb\request\request_interface::SERVER) || $this->user->data['username'] !== htmlspecialchars_decode($this->request->server('PHP_AUTH_USER')))
		{
			return $this->user->lang['SANDSTORM_SETUP_BEFORE_USE'];
		}
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function login($username, $password)
	{
		return array(
			'status'	=> LOGIN_ERROR_PASSWORD,
			'error_msg'	=> 'NO_PASSWORD_SUPPLIED',
			'user_row'	=> array('user_id' => ANONYMOUS),
		);
		// // do not allow empty password
		// if (!$password)
		// {
		// 	return array(
		// 		'status'	=> LOGIN_ERROR_PASSWORD,
		// 		'error_msg'	=> 'NO_PASSWORD_SUPPLIED',
		// 		'user_row'	=> array('user_id' => ANONYMOUS),
		// 	);
		// }

		// if (!$username)
		// {
		// 	return array(
		// 		'status'	=> LOGIN_ERROR_USERNAME,
		// 		'error_msg'	=> 'LOGIN_ERROR_USERNAME',
		// 		'user_row'	=> array('user_id' => ANONYMOUS),
		// 	);
		// }

		// if (!$this->request->is_set('PHP_AUTH_USER', \phpbb\request\request_interface::SERVER))
		// {
		// 	return array(
		// 		'status'		=> LOGIN_ERROR_EXTERNAL_AUTH,
		// 		'error_msg'		=> 'LOGIN_ERROR_EXTERNAL_AUTH_SANDSTORM',
		// 		'user_row'		=> array('user_id' => ANONYMOUS),
		// 	);
		// }

		// $php_auth_user = htmlspecialchars_decode($this->request->server('PHP_AUTH_USER'));
		// $php_auth_pw = htmlspecialchars_decode($this->request->server('PHP_AUTH_PW'));

		// if (!empty($php_auth_user) && !empty($php_auth_pw))
		// {
		// 	if ($php_auth_user !== $username)
		// 	{
		// 		return array(
		// 			'status'	=> LOGIN_ERROR_USERNAME,
		// 			'error_msg'	=> 'LOGIN_ERROR_USERNAME',
		// 			'user_row'	=> array('user_id' => ANONYMOUS),
		// 		);
		// 	}

		// 	$sql = 'SELECT user_id, username, user_password, user_passchg, user_email, user_type
		// 		FROM ' . USERS_TABLE . "
		// 		WHERE username = '" . $this->db->sql_escape($php_auth_user) . "'";
		// 	$result = $this->db->sql_query($sql);
		// 	$row = $this->db->sql_fetchrow($result);
		// 	$this->db->sql_freeresult($result);

		// 	if ($row)
		// 	{
		// 		// User inactive...
		// 		if ($row['user_type'] == USER_INACTIVE || $row['user_type'] == USER_IGNORE)
		// 		{
		// 			return array(
		// 				'status'		=> LOGIN_ERROR_ACTIVE,
		// 				'error_msg'		=> 'ACTIVE_ERROR',
		// 				'user_row'		=> $row,
		// 			);
		// 		}

		// 		// Successful login...
		// 		return array(
		// 			'status'		=> LOGIN_SUCCESS,
		// 			'error_msg'		=> false,
		// 			'user_row'		=> $row,
		// 		);
		// 	}

		// 	// this is the user's first login so create an empty profile
		// 	return array(
		// 		'status'		=> LOGIN_SUCCESS_CREATE_PROFILE,
		// 		'error_msg'		=> false,
		// 		'user_row'		=> $this->user_row($php_auth_user, $php_auth_pw),
		// 	);
		// }

		// // Not logged into sandstorm
		// return array(
		// 	'status'		=> LOGIN_ERROR_EXTERNAL_AUTH,
		// 	'error_msg'		=> 'LOGIN_ERROR_EXTERNAL_AUTH_SANDSTORM',
		// 	'user_row'		=> array('user_id' => ANONYMOUS),
		// );
	}

	/**
	 * {@inheritdoc}
	 */
	public function autologin()
	{
		print("<h1>autologged in</h1>");
		if (!$this->request->is_set('HTTP_X_SANDSTORM_USER_ID', \phpbb\request\request_interface::SERVER))
		{
		print("<h1>autologged in5</h1>");
			return array();
		}

		$php_auth_user = urldecode($this->request->server('HTTP_X_SANDSTORM_USERNAME'));
		$php_auth_pw = urldecode($this->request->server('HTTP_X_SANDSTORM_USER_ID'));
		$permissions = $this->request->is_set('HTTP_X_SANDSTORM_PERMISSIONS', \phpbb\request\request_interface::SERVER) ? $this->request->server('HTTP_X_SANDSTORM_PERMISSIONS') : '';

		print("<h1>autologged in2</h1>");
		if (!empty($php_auth_user) && !empty($php_auth_pw))
		{
			set_var($php_auth_user, $php_auth_user, 'string', true);
			set_var($php_auth_pw, $php_auth_pw, 'string', true);

			$sql = 'SELECT *
				FROM ' . USERS_TABLE . "
				WHERE username = '" . $this->db->sql_escape($php_auth_user) . "'";
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			// TODO(someday): handle upgrade/downgrade permissions.
			if ($row)
			{
				return ($row['user_type'] == USER_INACTIVE || $row['user_type'] == USER_IGNORE) ? array() : $row;
			}

			if (!function_exists('user_add'))
			{
				include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
			}
			// create the user if he does not exist yet
			user_add($this->user_row($php_auth_user, $php_auth_pw, strpos($permissions, 'admin') !== false ? USER_FOUNDER : USER_NORMAL));

			$sql = 'SELECT *
				FROM ' . USERS_TABLE . "
				WHERE username_clean = '" . $this->db->sql_escape(utf8_clean_string($php_auth_user)) . "'";
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

		print("<h1>autologged in3</h1>");
			if ($row)
			{
				return $row;
			}
		}

		return array();
	}

	/**
	 * This function generates an array which can be passed to the user_add
	 * function in order to create a user
	 *
	 * @param 	string	$username 	The username of the new user.
	 * @param 	string	$password 	The password of the new user.
	 * @return 	array 				Contains data that can be passed directly to
	 *								the user_add function.
	 */
	private function user_row($username, $password, $user_type)
	{
		// first retrieve default group id
		$sql = 'SELECT group_id
			FROM ' . GROUPS_TABLE . "
			WHERE group_name = '" . $this->db->sql_escape('REGISTERED') . "'
				AND group_type = " . GROUP_SPECIAL;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$row)
		{
			trigger_error('NO_GROUP');
		}

		// generate user account data
		return array(
			'username'		=> $username,
			'user_password'	=> $this->passwords_manager->hash($password),
			'user_email'	=> '',
			'group_id'		=> (int) $row['group_id'],
			'user_type'		=> $user_type,
			'user_ip'		=> $this->user->ip,
			'user_new'		=> ($this->config['new_member_post_limit']) ? 1 : 0,
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function validate_session($user)
	{
		// Check if PHP_AUTH_USER is set and handle this case
		if ($this->request->is_set('HTTP_X_SANDSTORM_USER_ID', \phpbb\request\request_interface::SERVER))
		{
			return true;
		}

		// PHP_AUTH_USER is not set. A valid session is now determined by the user type (anonymous/bot or not)
		if ($user['user_type'] == USER_IGNORE)
		{
			return true;
		}

		return false;
	}
}
