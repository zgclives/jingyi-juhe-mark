<?php

namespace JuheMark\BasicService;

use JuheMark\Kernel\Env;

$envFile = dirname(__DIR__, 2) . '/.env.juhe.mark';

if (!file_exists($envFile)) {
    copy(dirname(__DIR__, 1) . '/env.juhe.mark.example', $envFile);
    echo "首次加载，请修改JuheMark的配置文件: " . $envFile;
    chmod($envFile, 0400); // 更改访问权限，禁止用户在线读取
    exit();
} else {
    Env::loadFile($envFile);
}

trait BaseConfig
{
    /**
     * 初始化配置
     * @param array  $config           配置
     * @param string $serverMark       服务标识
     * @param string $isCheckConfigKey 是否检测config_key（公众号/小程序专用）
     * @return mixed
     */
    protected function initConfig($config, string $serverMark, $isCheckConfigKey = false)
    {
        // 请求地址
        $config['requestUrl'] = Env::get('system.requestUrl');
        $config['debug']      = Env::get('system.debug') ?: '';

        // 客户端KEY
        if (!isset($config['client_key']) || !$config['client_key']) {
            if ($client_key = Env::get('system.client_key')) {
                $config['client_key'] = $client_key;
            }
        }

        // 应用Key
        if (!isset($config['app_key']) || !$config['app_key']) {
            if ($app_key = Env::get('app.app_key')) {
                $config['app_key'] = $app_key;
            }
        }

        // 应用密钥
        if (!isset($config['app_secret']) || !$config['app_secret']) {
            if ($app_secret = Env::get('app.app_secret')) {
                $config['app_secret'] = $app_secret;
            }
        }

        // 预处理ConfigKey
        // $config = $this->handleConfigKey($config);

        // 检测必要配置项
        $this->checkConfig($config, $isCheckConfigKey);
        return $config;
    }

    /**
     * 检测必要配置项
     * @param $config
     */
    protected function checkConfig($config, $isCheckConfigKey)
    {
        // 客户端KEY
        if (!isset($config['client_key']) || !$config['client_key']) {
            $this->fail('聚合营销模块 client_key 不能为空');
        }

        // 应用Key
        if (!isset($config['app_key']) || !$config['app_key']) {
            $this->fail('聚合营销模块 app_key 不能为空');
        }

        // 应用密钥
        if (!isset($config['app_secret']) || !$config['app_secret']) {
            $this->fail('聚合营销模块 app_secret 不能为空');
        }
    }

    /**
     * 预处理ConfigKey
     * @param $config
     * @return mixed
     */
    private function handleConfigKey($config)
    {
        if (!isset($config['config_key'])) {
            return $config;
        }
        if ($temp = Env::get('mpConfigKey.' . $config['config_key'])) {
            $config['config_key'] = $temp;
        }
        return $config;
    }
}
