<?php
namespace App\Services;

class CookieService extends AppServiceAbstract
{
	protected array $options;

	public function __construct()
	{
		$timestamp = time() + 60*60*24*7; // 7 days
		$domain = env('SERVER_NAME');
		$this->options = [
			'expires' => $timestamp,
			'path' => '/',
			'secure' => false,
			'httponly' => true,
			'domain' => $domain,
			'samesite' => 'Lax'
		];
	}

	public function setOption(array $options) : self
	{
		if(!empty($options['expires']))
		{
			$this->options['expires'] = $options['expires'];
		}
		if(!empty($options['path']))
		{
			$this->options['path'] = $options['path'];
		}
		if(!empty($options['domain']))
		{
			$this->options['domain'] = $options['domain'];
		}
		if(!empty($options['secure']))
		{
			$this->options['secure'] = $options['secure'];
		}
		if(!empty($options['httponly']))
		{
			$this->options['httponly'] = $options['httponly'];
		}
		if(!empty($options['samesite']))
		{
			$this->options['samesite'] = $options['samesite'];
		}
		return $this;
	}

	public function set($cookie, $value, array $opts = [])
	{
		setcookie($cookie, $value, array_merge($this->options, $opts));
	}

	public function get($cookie)
	{
		return $_COOKIE[$cookie] ?? null;
	}

	public function remove($cookie)
	{
		setcookie($cookie, "", time() - 3600);
	}
}
?>