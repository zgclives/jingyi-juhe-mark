<?php

namespace JuheMark\SignIn;

use JuheMark\BasicService\BaseConfig;
use JuheMark\BasicService\RequestContainer;
use JuheMark\Kernel\Response;

/**
 * 兑换商城功能
 */
class Application extends RequestContainer
{
    use Response;
    use BaseConfig;

    // 服务标识
    private $serverMark = 'sign_in';

    public function __construct(array $config = [], array $prepends = [], string $id = null)
    {
        parent::__construct($this->initConfig($config, $this->serverMark), $this->serverMark, true);
    }

    /**
     * 获取配置
     * @return string
     */
    public function getConfig($user_id = 0)
    {
        return $this->httpPost($this->serverMark . '/getConfig', ['user_id' => $user_id]);
    }

    /**
     * 获取配置状态
     * @return string
     */
    public function getConfigStatus()
    {
        return $this->httpPost($this->serverMark . '/getConfigStatus');
    }

    /**
     * 获取配置规则
     * @return string
     */
    public function getConfigRules()
    {
        return $this->httpPost($this->serverMark . '/getConfigRules');
    }

    /**
     * 用户详情（拉新）
     * @param array $user 用户信息['user_id', 'name', 'avatar]
     * @return string
     */
    public function userDetail($user)
    {
        return $this->httpPost($this->serverMark . '/userDetail', ['user_id' => $user['user_id'], 'user' => $user]);
    }

    /**
     * 签到
     * @param array $user 用户信息['user_id', 'name', 'avatar]
     * @return string
     */
    public function signIn($user)
    {
        return $this->httpPost($this->serverMark . '/signIn', ['user' => $user]);
    }

    /**
     * 签到记录列表
     * @param int   $user_id 用户ID
     * @param array $query   查询条件
     * @return string
     */
    public function signInLogs(int $user_id, array $query = [])
    {
        $query['user_id'] = $user_id;
        return $this->httpPost($this->serverMark . '/signInLogs', $query);
    }

    /**
     * 领取连续签到奖励
     * @param int $user_id 领取人ID
     * @param int $log_id  领取信息ID
     * @return string
     */
    public function takeContinuityReward(int $user_id, int $log_id)
    {
        return $this->httpPost($this->serverMark . '/takeContinuityReward', [
            'user_id' => $user_id,
            'log_id'  => $log_id,
        ]);
    }

    /**
     * 连续签到奖励记录列表
     * @param int   $user_id 用户ID
     * @param array $query   查询条件
     * @return string
     */
    public function continuityRewardLogs(int $user_id, array $query = [])
    {
        $query['user_id'] = $user_id;
        return $this->httpPost($this->serverMark . '/continuityRewardLogs', $query);
    }

}
