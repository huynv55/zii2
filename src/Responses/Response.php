<?php
namespace App\Responses;
use App\Resources\ResourceInterface;

abstract class Response implements ResponseInterface
{
    /**
     * instance with the specified message body response.
     *
     * @var ResourceInterface
     */
    protected ?ResourceInterface $body;

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

    public function __construct()
    {
        $this->body = null;
        $this->headers = [];
        $this->statusCode = 200;
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
            if(strpos($key, $name) !== false) {
                $results[$key] = $value;
            }
        }
        return $results;
    }

    public function getHeaderLine($name): string
    {
        $results = [];
        foreach ($this->headers as $key => $value) {
            if(strpos($key, $name) !== false) {
                $results[] = $key.': '.$value;
            }
        }
        return !empty($results) ? implode("\n", $results) : '';
    }

    public function withHeader($name, $value): ResponseInterface
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

    public function getBody(): ?ResourceInterface
    {
        return $this->body;
    }

    public function setBody(?ResourceInterface $body = null) : ResponseInterface
    {
        $this->body = $body;
        return $this;
    }

    public function send(): string
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $key => $value) {
            header($key.': '.$value);
        }
        return '';
    }
}

?>