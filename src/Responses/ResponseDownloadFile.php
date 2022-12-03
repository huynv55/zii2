<?php
namespace App\Responses;


class ResponseDownloadFile extends ResponseAbstract
{
    protected string $path = '';

    public function setPath(string $pathToFile) : self
    {
        $this->path = $pathToFile;
        return $this;
    }

    public function entities_to_unicode($str, $flags) {
        $str = html_entity_decode($str, $flags,'UTF-8');
        $str = preg_replace_callback("/(&#[0-9]+;)/", function($m) {
            return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
        }, $str);
        return $str;
    }

    public function download()
    {
        if (!file_exists($this->path))
        {
            $file = writeTemporaryFile(file_get_contents($this->path));
            $body = file_get_contents($file);
            $contentType = mime_content_type($file);
            $contentLength = filesize($file);
        }
        else
        {
            $body = file_get_contents($this->path);
            $contentType = mime_content_type($this->path);
            $contentLength = filesize($this->path);
        }
        //dd($contentLength);
        $file_name = basename($this->path);
        
        $raw_name = sprintf("Content-disposition:attachment;filename=%s", $this->entities_to_unicode($file_name,ENT_QUOTES));
        $this
        ->setBody($body)
        ->withHeader('Content-Description', 'File Transfer')
        ->withHeader('Content-Type', $contentType)
        ->withHeader('Content-Length', $contentLength)
        ->withHeader('Content-Disposition', $raw_name)
        ->send();
    }
}

?>