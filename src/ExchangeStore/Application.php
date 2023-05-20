<?php

namespace JuheMark\ExchangeStore;

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
    private $serverMark = 'exchange_store';

    public function __construct(array $config = [], array $prepends = [], string $id = null)
    {
        parent::__construct($this->initConfig($config, $this->serverMark), $this->serverMark, true);
    }

    /**
     * 获取配置
     * @return string
     */
    public function getConfig()
    {
        return $this->httpPost($this->serverMark . '/getConfig');
    }

    /**
     * 用户详情（拉新）
     * @param array $user 用户对象
     * @return string
     */
    public function userDetail($user)
    {
        return $this->httpPost($this->serverMark . '/userDetail', ['user_id' => $user['user_id'], 'user' => $user]);
    }

    /**
     * 兑换信息列表
     * @param array $query 查询条件
     * @return string
     */
    public function exchangeInfoList(array $query = [])
    {
        return $this->httpPost($this->serverMark . '/exchangeInfoList', $query);
    }

    /**
     * 兑换信息详情
     * @param string $third_exchange_id 兑换信息ID
     * @return string
     */
    public function exchangeInfoDetail($third_exchange_id)
    {
        return $this->httpPost($this->serverMark . '/exchangeInfoDetail', [
            'third_exchange_id' => $third_exchange_id
        ]);
    }

    /**
     * 兑换
     * @param int    $user_id           兑换人ID
     * @param string $third_exchange_id 兑换信息ID
     * @param int    $quantity          兑换数量
     * @param string $remark            说明
     * @return string
     */
    public function exchange(int $user_id, string $third_exchange_id, int $quantity, string $remark = '')
    {
        return $this->httpPost($this->serverMark . '/exchange', [
            'user_id'           => $user_id,
            'third_exchange_id' => $third_exchange_id,
            'quantity'          => $quantity,
            'remark'            => $remark,
        ]);
    }

    /**
     * 用户兑换记录列表
     * @param int   $user_id 兑换人ID
     * @param array $query   查询条件
     * @return string
     */
    public function userExchangeLogs(int $user_id, array $query = [])
    {
        $query['user_id'] = $user_id;
        return $this->httpPost($this->serverMark . '/userExchangeLogs', $query);
    }

    /**
     * 用户兑换记录详情
     * @param int $user_id 兑换人ID
     * @param int $log_id  兑换记录ID
     * @return string
     */
    public function userExchangeLogDetail(int $user_id, int $exchange_log_id)
    {
        return $this->httpPost($this->serverMark . '/userExchangeLogDetail', [
            'user_id'         => $user_id,
            'exchange_log_id' => $exchange_log_id,
        ]);
    }

    /**
     * 手动操作余额
     * @param int    $user_id 用户ID
     * @param int    $amount  操作金额（单位：厘）
     * @param string $kind    操作方式：INC=增加,DEC=扣减
     * @param string $remark  操作说明
     * @return string
     */
    public function balanceOperate(int $user_id, int $amount, string $kind, string $remark)
    {
        return $this->httpPost($this->serverMark . '/balanceOperate', [
            'user_id' => $user_id,
            'amount'  => $amount,
            'kind'    => $kind,
            'remark'  => $remark,
        ]);
    }

    /**
     * 完成任务
     * @param array  $user      用户信息['user_id', 'name']
     * @param string $task_type 任务类型
     * @param array  $task_info 任务信息
     * @return string
     */
    public function completeTask($user, $task_type, $task_info)
    {
        return $this->httpPost($this->serverMark . '/completeTask', [
            'user'      => $user,
            'task_type' => $task_type,
            'task_info' => $task_info,
        ]);
    }


}
