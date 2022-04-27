<?php

namespace JuheMark\Common\Response;

use JuheMark\BasicService\RequestContainer;

class ServiceProvider extends RequestContainer
{
    private $http = [
        100 => "HTTP/1.1 100 Continue",
        101 => "HTTP/1.1 101 Switching Protocols",
        200 => "HTTP/1.1 200 OK",
        201 => "HTTP/1.1 201 Created",
        202 => "HTTP/1.1 202 Accepted",
        203 => "HTTP/1.1 203 Non-Authoritative Information",
        204 => "HTTP/1.1 204 No Content",
        205 => "HTTP/1.1 205 Reset Content",
        206 => "HTTP/1.1 206 Partial Content",
        300 => "HTTP/1.1 300 Multiple Choices",
        301 => "HTTP/1.1 301 Moved Permanently",
        302 => "HTTP/1.1 302 Found",
        303 => "HTTP/1.1 303 See Other",
        304 => "HTTP/1.1 304 Not Modified",
        305 => "HTTP/1.1 305 Use Proxy",
        307 => "HTTP/1.1 307 Temporary Redirect",
        400 => "HTTP/1.1 400 Bad Request",
        401 => "HTTP/1.1 401 Unauthorized",
        402 => "HTTP/1.1 402 Payment Required",
        403 => "HTTP/1.1 403 Forbidden",
        404 => "HTTP/1.1 404 Not Found",
        405 => "HTTP/1.1 405 Method Not Allowed",
        406 => "HTTP/1.1 406 Not Acceptable",
        407 => "HTTP/1.1 407 Proxy Authentication Required",
        408 => "HTTP/1.1 408 Request Time-out",
        409 => "HTTP/1.1 409 Conflict",
        410 => "HTTP/1.1 410 Gone",
        411 => "HTTP/1.1 411 Length Required",
        412 => "HTTP/1.1 412 Precondition Failed",
        413 => "HTTP/1.1 413 Request Entity Too Large",
        414 => "HTTP/1.1 414 Request-URI Too Large",
        415 => "HTTP/1.1 415 Unsupported Media Type",
        416 => "HTTP/1.1 416 Requested range not satisfiable",
        417 => "HTTP/1.1 417 Expectation Failed",
        500 => "HTTP/1.1 500 Internal Server Error",
        501 => "HTTP/1.1 501 Not Implemented",
        502 => "HTTP/1.1 502 Bad Gateway",
        503 => "HTTP/1.1 503 Service Unavailable",
        504 => "HTTP/1.1 504 Gateway Time-out",
    ];
    
    public function __construct($config = null)
    {
        parent::__construct($config);
    }
    
    /**
     * 响应(如：直接输出图片，微信的XML数据)
     *
     * @param null   $fn     回调里面直接echo
     * @param string $format 响应格式，默认为xml
     * @param int    $status 响应状态，默认为200
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function output($fn = null, $format = 'xml', $status = 200)
    {
        header($this->http[$status]);
        
        // 图片类
        if (in_array($format, ['gif', 'jpeg', 'png', 'jpg'])) {
            if ($format == 'jpg') {
                $format = 'jpeg';
            }
            $headers = ["Content-type" => "image/{$format}"];
            
            // application类
        } elseif (in_array($format, [
            'json',
            'xhtml+xml',
            'atom+xml',
            'pdf',
            'msword',
            'octet-stream',
            'x-www-form-urlencoded',
        ])) {
            $headers = ["Content-type" => "application/{$format}"];
            
            // 文本类 html|plain|xml
        } else {
            $headers = ["Content-type" => "text/{$format}"];
        }
        foreach ($headers as $key => $head) {
            header("{$key}: {$head}");
        }
        $fn && $fn($this);
        exit();
    }
    
    public function deBase64Image($value)
    {
        if (strpos($value, 'data:image/png;base64,') === false) {
            return $value;
        }
        $temp = explode(':image/png;base64,', $value);
        return base64_decode($temp[1]);
    }
    
    public function enBase64Image($image)
    {
        return 'data:image/png;base64,' . base64_decode($image);
    }
    
}
