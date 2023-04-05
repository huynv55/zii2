<?php
namespace App\Services;

class SessionService extends AbstractService
{
    const SESSION_NAME = 'session';

    public const META_CREATED = 'c';
    public const META_UPDATED = 'u';

    private static bool $started = false;

    public function __construct()
    {
        
    }

    public function initialize()
    {
        parent::initialize();
    }

    public function start()
    {
        if (self::$started) {
            return true;
        }

        if (\PHP_SESSION_ACTIVE === session_status()) {
            self::$started = true;
            return true;
        }

        if (filter_var(\ini_get('session.use_cookies'), \FILTER_VALIDATE_BOOL) && headers_sent($file, $line)) {
            throw new \RuntimeException(sprintf('Failed to start the session because headers have already been sent by "%s" at line %d.', $file, $line));
        }

        $sessionId = $_COOKIE[session_name()] ?? null;
        /*
         * Explanation of the session ID regular expression: `/^[a-zA-Z0-9,-]{22,250}$/`.
         *
         * ---------- Part 1
         *
         * The part `[a-zA-Z0-9,-]` is related to the PHP ini directive `session.sid_bits_per_character` defined as 6.
         * See https://www.php.net/manual/en/session.configuration.php#ini.session.sid-bits-per-character.
         * Allowed values are integers such as:
         * - 4 for range `a-f0-9`
         * - 5 for range `a-v0-9`
         * - 6 for range `a-zA-Z0-9,-`
         *
         * ---------- Part 2
         *
         * The part `{22,250}` is related to the PHP ini directive `session.sid_length`.
         * See https://www.php.net/manual/en/session.configuration.php#ini.session.sid-length.
         * Allowed values are integers between 22 and 256, but we use 250 for the max.
         *
         * Where does the 250 come from?
         * - The length of Windows and Linux filenames is limited to 255 bytes. Then the max must not exceed 255.
         * - The session filename prefix is `sess_`, a 5 bytes string. Then the max must not exceed 255 - 5 = 250.
         *
         * ---------- Conclusion
         *
         * The parts 1 and 2 prevent the warning below:
         * `PHP Warning: SessionHandler::read(): Session ID is too long or contains illegal characters. Only the A-Z, a-z, 0-9, "-", and "," characters are allowed.`
         *
         * The part 2 prevents the warning below:
         * `PHP Warning: SessionHandler::read(): open(filepath, O_RDWR) failed: No such file or directory (2).`
         */
        if (!empty($sessionId) and !preg_match('/^[a-zA-Z0-9,-]{22,250}$/', $sessionId)) {
            // the session ID in the header is invalid, create a new one
            session_id(session_create_id());
        }

        // ok to try and start the session
        if (!session_start()) {
            throw new \RuntimeException('Failed to start the session.');
        }

        self::$started = true;
        return true;
    }

    public function generateKey(string $name): string
    {
        return static::SESSION_NAME.'_'.$name;
    }

    public function isStarted(): bool
    {
        return self::$started;
    }

    public function has(string $name): bool
    {
        return isset($_SESSION[$this->generateKey($name)]);
    }

    public function get(string $name, mixed $default = null)
    {
        return $this->has($name) ? $_SESSION[$this->generateKey($name)] : $default;
    }

    public function set(string $name, mixed $value): void
    {
        $_SESSION[$this->generateKey($name)] = $value;
    }

    public function getAll(): array
    {
        $sessions = [];
        foreach ($_SESSION as $key => $value) {
            if (strpos($key, static::SESSION_NAME) === 0) {
                $sessions[$key] = $value;
            }
        }
        return $sessions;
    }

    public function replace(array $sessions)
    {
        foreach ($sessions as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function remove(string $name): mixed
    {
        $strval = null;
        if($this->has($name))
        {
            $strval = $this->get($name);
            unset($_SESSION[$this->generateKey($name)]);
        }
        return $strval;
    }

    public function destroy(): void
    {
        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Finally, destroy the session.
        session_destroy();
        self::$started = false;
    }
}
?>