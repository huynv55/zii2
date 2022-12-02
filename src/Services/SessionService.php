<?php
namespace App\Services;

class SessionService extends AppServiceAbstract
{
    const SESSION_NAME = 'session_';
    const SESSION_FLASH_NAME = 'flash_session_';
    const SESSION_LIFT_TIME = 86400;

    /**
     * Determine if session has started.
     *
     * @var bool
     */
    protected bool $sessionStarted = false;

    public function __construct()
    {
        $this->init();
    }
    

    public function init($lifeTime = null)
    {
        $timeout = !empty($lifeTime) ? $lifeTime : self::SESSION_LIFT_TIME;
        if (session_status() !== PHP_SESSION_ACTIVE) {
            $this->sessionStarted = session_start([
                'cookie_lifetime' => $timeout
            ]);
        } else {
            $this->sessionStarted = true;
        }
        return $this->sessionStarted;
    }

    /**
     * Get session id.
     *
     * @return string → the session id or empty
     */
    public function id()
    {
        return session_id();
    }

    /**
     * Regenerate session_id.
     *
     * @return string → session_id
     */
    public function regenerate()
    {
        session_regenerate_id(true);
        return session_id();
    }

    public function put($key, $data) {
        $_SESSION[self::SESSION_NAME.$key] = $data;
    }

    public function get($key) {
        if(!empty($_SESSION[self::SESSION_NAME.$key])) {
            return $_SESSION[self::SESSION_NAME.$key];
        } else {
            return null;
        }
    }

    public function remove($key) {
        if(!empty($_SESSION[self::SESSION_NAME.$key])) {
            unset($_SESSION[self::SESSION_NAME.$key]);
        }
    }

    public function destroy() {
        if (self::$sessionStarted == true) {
            session_unset();
            session_destroy();
            return true;
        }
        return false;
    }

    public function putFlashData($key, $data) {
        $_SESSION[self::SESSION_FLASH_NAME.$key] = $data;
    }

    public function getFlashData($key) {
        if(!empty($_SESSION[self::SESSION_FLASH_NAME.$key])) {
            $res = $_SESSION[self::SESSION_FLASH_NAME.$key];
            unset($_SESSION[self::SESSION_FLASH_NAME.$key]);
            return $res;
        } else {
            return null;
        }
    }

    public function pull($key)
    {
        $value = $this->get($key);
        $this->remove($key);
        return $value;
    }

    public function csrfValidate($token) {
        $csrf = $this->csrfToken();
        $isValid = $csrf == $token;
        unset($_SESSION['delete_customer_token']);
        return $isValid;
    }

    public function refreshCsrf() {
        $csrf = md5(openssl_random_pseudo_bytes(12));
        $_SESSION['delete_customer_token']= $csrf;
        return $csrf;
    }

    public function csrfInput() {
        return '<input name="csrf_token" type="hidden" value="'.$this->csrfToken().'" />';
    }

    public function csrfToken() {
        $csrf = isset($_SESSION['delete_customer_token']) ? $_SESSION['delete_customer_token'] : "";
        if (empty($csrf)) {
            // generate token and persist for later verification
            // - in practice use openssl_random_pseudo_bytes() or similar instead of uniqid() 
            $csrf = md5(openssl_random_pseudo_bytes(12));
            $_SESSION['delete_customer_token']= $csrf;
        }
        return $csrf;
    }

    public function saveAuth($user)
    {
        $this->put('auth', $user);
    }

    public function getAuth()
    {
        return $this->get('auth');
    }
}
?>