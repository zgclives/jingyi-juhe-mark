<?php

namespace JuheMark\Kernel;

use GuzzleHttp\Client;

class Http
{
    public static function httpGet(string $url, array $query = [], array $header = [])
    {
        $options = ['query' => $query];
        if ($header) {
            $options['header'] = $header;
        }
        return self::request($url, 'GET', $options);
    }

    public static function httpPost(string $url, array $data = [], array $headers = [])
    {
        $options = ['form_params' => $data];
        if ($headers) {
            $options['headers'] = $headers;
        }
        return self::request($url, 'POST', $options);
    }

    public static function httpPostJson(string $url, array $data = [], array $query = [], $returnJson)
    {
        return self::request($url, 'POST', ['query' => $query, 'json' => $data], $returnJson);
    }

    public static function httpUpload(string $url, array $files = [], array $form = [], array $query = [])
    {
        $multipart = [];

        foreach ($files as $name => $path) {
            $filename    = pathinfo($path, PATHINFO_BASENAME);
            $multipart[] = [
                'name'     => $name,
                'contents' => file_exists($path) ? fopen($path, 'r') : '',
                'headers'  => [
                    'Content-Disposition' => 'form-data; name="' . $name . '"; filename="' . $filename . '"',
                ],
            ];
        }
        foreach ($form as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }

        return self::request(
            $url,
            'POST',
            [
                'query'           => $query,
                'multipart'       => $multipart,
                'connect_timeout' => 60,
                'timeout'         => 60,
                'read_timeout'    => 60,
            ]
        );
    }

    public static function request($url, $method = 'GET', array $options = [], $returnJson = true)
    {
        $options['http_errors'] = false;
        $client                 = new Client();
        return self::handleResponse($client->request($method, $url, $options), $returnJson);
    }

    /**
     * 处理响应内容
     * @param $request
     * @return mixed
     */
    public static function handleResponse($request, $returnJson)
    {
        $res = $request->getBody()->getContents();
        if ($returnJson && !is_null($temp = json_decode($res, true))) {
            return $temp;
        }
        return $res;
    }
}
