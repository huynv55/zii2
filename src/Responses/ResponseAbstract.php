<?php
namespace App\Responses;

abstract class ResponseAbstract implements ResponseInterface
{
    /**
     * all message header values response
     *
     * @var array
     */
    protected array $headers;

    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * If no reason phrase is specified, implementations MAY choose to default
     * to the RFC 7231 or IANA recommended reason phrase for the response's
     * status code.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated status and reason phrase.
     *
     */
    protected int $statusCode;

    protected string $content;

    public function __construct()
    {
        $this->body = null;
        $this->headers = app()->getResponseHeaders();
        $this->statusCode = 200;
        $this->content = '';
    }

    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    public function withStatus($code) : ResponseInterface
    {
        $this->statusCode = $code;
        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        foreach ($this->headers as $key => $value) {
            if($key === $name) {
                return true;
            }
        }
        return false;
    }

    public function getHeader($name): array
    {
        $results = [];
        foreach ($this->headers as $key => $value) {
            if(strpos(strtolower(trim($key)), strtolower(trim($name))) !== false) {
                $results[strtolower(trim($key))] = $value;
            }
        }
        return $results;
    }

    public function getHeaderLine($name): string
    {
        $results = [];
        foreach ($this->headers as $key => $value) {
            if(strpos(strtolower(trim($key)), strtolower(trim($name))) !== false) {
                $results[] = strtolower(trim($key)).': '.$value;
            }
        }
        return !empty($results) ? implode("\n", $results) : '';
    }

    public function withHeader($name, $value = ''): ResponseInterface
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function withoutHeader($name): ResponseInterface
    {
        if(in_array($name, array_keys($this->headers)))
        {
            unset($this->headers[$name]);
        }
        return $this;
    }

    public function getBody(): ?string
    {
        return $this->content;
    }

    public function setBody(?string $body) : ResponseInterface
    {
        $this->content = $body;
        return $this;
    }

    public function send()
    {
        http_response_code($this->statusCode);
        if(!headers_sent())
        {
            foreach ($this->headers as $key => $value) {
                if (empty($value)) {
                    header($key);
                } else {
                    header($key.': '.$value);
                }
            }
        }
        echo $this->content;
        die();
    }
}

?>