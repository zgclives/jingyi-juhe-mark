<?php

namespace JuheMark\BasicService;

use JuheMark\Kernel\Http;

class RequestContainer
{
    protected $config = [];
    private $serverMark = '';

    public function __construct($config = null, $serverMark = '')
    {
        $this->config     = $config;
        $this->serverMark = $serverMark;
    }

    /**
     * 聚合权限接口专用
     * @param array $data
     * @param array $query
     * @param bool  $returnJson
     * @return mixed
     */
    protected function httpPostJsonAuth($data = [], $query = [], $returnJson = true)
    {
        return Http::httpPostJson(
            sprintf('%sam/%s', $this->config['requestUrl'], $this->config['project_auth_key']),
            array_merge($data, ['debug' => $this->config['debug']]),
            $query,
            $returnJson
        );
    }

    /**
     * 聚合服务1.0，普通接口专用
     * @param array $data
     * @param array $query
     * @param bool  $returnJson
     * @return mixed
     */
    protected function httpPostJsonV1($data = [], $query = [], $returnJson = true)
    {
        return Http::httpPostJson(
            sprintf('%sk/%s/%s', $this->config['requestUrl'], $this->config['project_key'], $this->serverMark),
            array_merge($data, ['debug' => $this->config['debug']]),
            $query,
            $returnJson
        );
    }

    /**
     * 聚合服务1.0，上传接口专用
     * @param array $files
     * @param array $form
     * @param array $query
     * @return mixed
     */
    protected function httpUploadV1($files = [], $form = [], $query = [])
    {
        return Http::httpUpload(
            sprintf('%sk/%s/%s', $this->config['requestUrl'], $this->config['project_key'], $this->serverMark),
            $files,
            array_merge($form, ['debug' => $this->config['debug']]),
            $query
        );
    }

    /**
     * 聚合服务2.0，普通接口专用
     * @param array $data
     * @param array $query
     * @param bool  $returnJson
     * @return mixed
     */
    protected function httpPostJsonV2($data = [], $query = [], $returnJson = true)
    {
        return Http::httpPostJson(
            sprintf('%sk/%s', $this->config['requestUrl'], $this->config['config_key']),
            array_merge($data, ['debug' => $this->config['debug']]),
            $query,
            $returnJson
        );
    }

    /**
     * 聚合服务2.0，上传接口专用
     * @param array $files
     * @param array $form
     * @param array $query
     * @return mixed
     */
    protected function httpUploadV2($files = [], $form = [], $query = [])
    {
        return Http::httpUpload(
            sprintf('%sk/%s', $this->config['requestUrl'], $this->config['config_key']),
            $files,
            array_merge($form, ['debug' => $this->config['debug']]),
            $query
        );
    }

    /**
     * 简单安全过虑
     * @param string $name
     * @param string $defaltu
     * @return mixed|string
     */
    protected function input($name, $defaltu = '')
    {
        if (isset($_GET[$name])) {
            return addslashes($_GET[$name]);
        } elseif (isset($_POST[$name])) {
            return addslashes($_POST[$name]);
        } else {
            return $defaltu;
        }
    }

    /**
     * 请求头信息
     * @return array
     */
    protected function getHeader()
    {
        // 固定的header信息
        return [
            'client-key' => $this->config['client_key'],
        ];
    }

    /**
     * 签名
     * @param $params
     * @return string
     */
    protected function getSign($params): string
    {
        // 正序排序
        ksort($params);
        // 应用密钥
        $str = $this->config['app_secret'];

        // 拼接字符串
        foreach ($params as $key => $value) {
            if (!$value || $key == 'file') {
                continue;
            }
            $str .= $key . $value;
        }

        // md5加密转大写
        return strtoupper(md5($str));
    }

    /**
     * 统一请求
     * @param string $action 接口名
     * @param array  $data   请求参数
     * @return string
     */
    protected function httpPost(string $action, array $data = [])
    {
        // 固定的请求参数
        $params = [
            // 'debug'     => $this->config['debug'],
            'app_key'   => $this->config['app_key'],
            'timestamp' => time(),
            'data'      => json_encode($data)
        ];

        // 参数签名
        $params['sign'] = $this->getSign($params);
        // dd($params);

        // 接口链接
        $url = $this->config['requestUrl'] . $action;

        return Http::httpPost($url, $params, $this->getHeader());
    }
}
