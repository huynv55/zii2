<?php
namespace App\Responses;

use initializeLoader;

abstract class AbstractResponse implements initializeLoader
{
    public const HTTP_CONTINUE = 100;
    public const HTTP_SWITCHING_PROTOCOLS = 101;
    public const HTTP_PROCESSING = 102;            // RFC2518
    public const HTTP_EARLY_HINTS = 103;           // RFC8297
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_ACCEPTED = 202;
    public const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
    public const HTTP_NO_CONTENT = 204;
    public const HTTP_RESET_CONTENT = 205;
    public const HTTP_PARTIAL_CONTENT = 206;
    public const HTTP_MULTI_STATUS = 207;          // RFC4918
    public const HTTP_ALREADY_REPORTED = 208;      // RFC5842
    public const HTTP_IM_USED = 226;               // RFC3229
    public const HTTP_MULTIPLE_CHOICES = 300;
    public const HTTP_MOVED_PERMANENTLY = 301;
    public const HTTP_FOUND = 302;
    public const HTTP_SEE_OTHER = 303;
    public const HTTP_NOT_MODIFIED = 304;
    public const HTTP_USE_PROXY = 305;
    public const HTTP_RESERVED = 306;
    public const HTTP_TEMPORARY_REDIRECT = 307;
    public const HTTP_PERMANENTLY_REDIRECT = 308;  // RFC7238
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_PAYMENT_REQUIRED = 402;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_NOT_ACCEPTABLE = 406;
    public const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
    public const HTTP_REQUEST_TIMEOUT = 408;
    public const HTTP_CONFLICT = 409;
    public const HTTP_GONE = 410;
    public const HTTP_LENGTH_REQUIRED = 411;
    public const HTTP_PRECONDITION_FAILED = 412;
    public const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
    public const HTTP_REQUEST_URI_TOO_LONG = 414;
    public const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
    public const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    public const HTTP_EXPECTATION_FAILED = 417;
    public const HTTP_I_AM_A_TEAPOT = 418;                                               // RFC2324
    public const HTTP_MISDIRECTED_REQUEST = 421;                                         // RFC7540
    public const HTTP_UNPROCESSABLE_ENTITY = 422;                                        // RFC4918
    public const HTTP_LOCKED = 423;                                                      // RFC4918
    public const HTTP_FAILED_DEPENDENCY = 424;                                           // RFC4918
    public const HTTP_TOO_EARLY = 425;                                                   // RFC-ietf-httpbis-replay-04
    public const HTTP_UPGRADE_REQUIRED = 426;                                            // RFC2817
    public const HTTP_PRECONDITION_REQUIRED = 428;                                       // RFC6585
    public const HTTP_TOO_MANY_REQUESTS = 429;                                           // RFC6585
    public const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;                             // RFC6585
    public const HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451;                               // RFC7725
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    public const HTTP_NOT_IMPLEMENTED = 501;
    public const HTTP_BAD_GATEWAY = 502;
    public const HTTP_SERVICE_UNAVAILABLE = 503;
    public const HTTP_GATEWAY_TIMEOUT = 504;
    public const HTTP_VERSION_NOT_SUPPORTED = 505;
    public const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;                        // RFC2295
    public const HTTP_INSUFFICIENT_STORAGE = 507;                                        // RFC4918
    public const HTTP_LOOP_DETECTED = 508;                                               // RFC5842
    public const HTTP_NOT_EXTENDED = 510;                                                // RFC2774
    public const HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;                             // RFC6585

    /**
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control
     */
    private const HTTP_RESPONSE_CACHE_CONTROL_DIRECTIVES = [
        'must_revalidate' => false,
        'no_cache' => false,
        'no_store' => false,
        'no_transform' => false,
        'public' => false,
        'private' => false,
        'proxy_revalidate' => false,
        'max_age' => true,
        's_maxage' => true,
        'stale_if_error' => true,         // RFC5861
        'stale_while_revalidate' => true, // RFC5861
        'immutable' => false,
        'last_modified' => true,
        'etag' => true,
    ];

    protected ?string $content = null;
    protected array $headers = [];
    protected array $cookies = [];
    protected int $statusCode = 200;
    /**
     * @var string
     */
    protected $statusText;

    public static $statusTexts = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',            // RFC2518
        103 => 'Early Hints',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',          // RFC4918
        208 => 'Already Reported',      // RFC5842
        226 => 'IM Used',               // RFC3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',    // RFC7238
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Content Too Large',                                           // RFC-ietf-httpbis-semantics
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',                                               // RFC2324
        421 => 'Misdirected Request',                                         // RFC7540
        422 => 'Unprocessable Content',                                       // RFC-ietf-httpbis-semantics
        423 => 'Locked',                                                      // RFC4918
        424 => 'Failed Dependency',                                           // RFC4918
        425 => 'Too Early',                                                   // RFC-ietf-httpbis-replay-04
        426 => 'Upgrade Required',                                            // RFC2817
        428 => 'Precondition Required',                                       // RFC6585
        429 => 'Too Many Requests',                                           // RFC6585
        431 => 'Request Header Fields Too Large',                             // RFC6585
        451 => 'Unavailable For Legal Reasons',                               // RFC7725
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',                                     // RFC2295
        507 => 'Insufficient Storage',                                        // RFC4918
        508 => 'Loop Detected',                                               // RFC5842
        510 => 'Not Extended',                                                // RFC2774
        511 => 'Network Authentication Required',                             // RFC6585
    ];

    public function initialize()
    {
        $this->setStatusCode(self::HTTP_OK);
    }

    /**
     * Sets the response status code.
     *
     * If the status text is null it will be automatically populated for the known
     * status codes and left empty otherwise.
     *
     * @return $this
     *
     * @final
     */
    public function setStatusCode(int $code, string $text = null): static
    {
        $this->statusCode = $code;

        if (null === $text) {
            $this->statusText = self::$statusTexts[$code] ?? 'unknown status';

            return $this;
        }

        if (false === $text) {
            $this->statusText = '';

            return $this;
        }

        $this->statusText = $text;

        return $this;
    }

    /**
     * Sets the response content.
     *
     */
    public function setContent(?string $content)
    {
        $this->content = $content ?? '';
    }

    /**
     * Sends content for the current web response.
     *
     * @return $this
     */
    public function emitContent(): static
    {
        echo $this->content;

        return $this;
    }

    public function emitHeaders(): static
    {
        // headers have already been sent by the developer
        if (headers_sent()) {
            return $this;
        }

        // headers
        
        foreach ($this->headers as $header => $value) {
            header($header.': '.$value, true, $this->statusCode);
        }

        // cookies
        foreach ($this->cookies as $cookie) {
            header('Set-Cookie: '.$this->cookieToString($cookie), false, $this->statusCode);
        }

        // status
        header(sprintf('HTTP/%s %s %s', '1.1', $this->statusCode, $this->statusText), true, $this->statusCode);

        return $this;
    }

    public function send()
    {
        $this
            ->emitHeaders()
            ->emitContent();
        die();
    }

    /**
     * Returns the cookie as a string.
     */
    private function cookieToString(array $cookie): string
    {
        $str = str_replace(['=', ',', ';', ' ', "\t", "\r", "\n", "\v", "\f"], ['%3D', '%2C', '%3B', '%20', '%09', '%0D', '%0A', '%0B', '%0C'], $cookie['name']);

        $str .= '=';

        if (empty($cookie['value'])) {
            $str .= 'deleted; expires='.gmdate('D, d M Y H:i:s T', time() - 31536001).'; Max-Age=0';
        } else {
            $str .= rawurlencode(bin2hex($cookie['value']));

            if (!empty($cookie['expires'])) {
                $str .= '; expires='.gmdate('D, d M Y H:i:s T', $cookie['expires']).'; Max-Age='.($cookie['expires'] > time() ? $cookie['expires'] - time(): 0);
            }
        }

        if (!empty($cookie['path'])) {
            $str .= '; path='.$cookie['path'];
        } else {
            $str .= '; path=/';
        }

        if (!empty($cookie['domain'])) {
            $str .= '; domain='.$cookie['domain'];
        }

        if (true === $cookie['secure']) {
            $str .= '; secure';
        }

        if (true === $cookie['httponly']) {
            $str .= '; httponly';
        }

        if (isset($cookie['samesite']) and null !== $cookie['samesite']) {
            $str .= '; samesite='.$cookie['samesite'];
        }

        return $str;
    }
    
}

?>