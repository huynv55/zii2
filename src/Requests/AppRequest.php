<?php
namespace App\Requests;
use Rakit\Validation\Validator;
use Rakit\Validation\Validation;

abstract class AppRequest implements RequestInterface
{
    /**
     * array list of request headers
     *
     * @var array
     */
    protected   array $headers;

    /**
     * method of request GET, POST, PUT, OPTIONS or DELETE
     *
     * @var string
     */
    protected   string $method;

    /**
     * post data request
     *
     * @var array
     */
    protected   array $post;

    /**
     * query params request
     *
     * @var array
     */
    protected   array $get;

    /**
     * array list files uploaded 
     *
     * @var array
     */
    protected   array $files;

    /**
     * array validate config
     * more detail https://github.com/rakit/validation
     * @var array
     */
    public      array $validate = [];

    /**
     * array list messages validation
     *
     * @var array
     */
    public      array $messages = [];
    protected   Validation $validation;

    public function __construct()
    {
        $this->headers = apache_request_headers();
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->post = $_POST;
        $this->get = $_GET;
        $this->files = $_FILES;
        $validator = new Validator($this->messages);
        $this->validation = $validator->validate(array_merge($this->files, $this->post, $this->get), $this->validate);
    }

    public function getProtocolVersion()
    {
        return $_SERVER['SERVER_PROTOCOL'];
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getHeaders() : array
    {
        return $this->headers;
    }

    public function hasHeader($name) : bool
    {
        return in_array($name, $this->headers);
    }

    public function getHeader($name) : array
    {
        $results = [];
        foreach ($this->headers as $key => $value) {
            if(strpos(strtolower(trim($key)), strtolower(trim($name))) !== false) {
                $results[strtolower(trim($key))] = $value;
            }
        }
        return $results;
    }

    public function getHeaderLine($name) : string
    {
        $results = [];
        foreach ($this->headers as $key => $value) {
            if(strpos(strtolower(trim($key)), strtolower(trim($name))) !== false) {
                $results[] = strtolower(trim($key)).': '.$value;
            }
        }
        return !empty($results) ? implode("\n", $results) : '';
    }

    public function getQueryString() : array
    {
        return $this->get;
    }

    public function getPostData() : array
    {
        return $this->post;
    }

    public function getFileUpload() : array
    {
        return $this->files;
    }

    public function getUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * check validate request
     *
     * @return boolean
     */
    public function validated() : bool
    {
        return $this->validation->passes();
    }
    
    public function getValidateErrors()
    {
        return $this->validation->errors();
    }

    public function authorize() : bool
    {
        return true;
    }

    public function user()
    {
        return [];
    }
} 
?>