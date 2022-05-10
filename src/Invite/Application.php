<?php

namespace JuheMark\Invite;

use JuheMark\BasicService\BaseConfig;
use JuheMark\BasicService\RequestContainer;
use JuheMark\Kernel\Response;

/**
 * Class Application.
 */
class Application extends RequestContainer
{
    use Response;
    use BaseConfig;

    // 服务标识
    private $serverMark = 'invite';

    public function __construct(array $config = [], array $prepends = [], string $id = null)
    {
        parent::__construct($this->initConfig($config, $this->serverMark), $this->serverMark, true);
    }

    /**
     * 初始化拉新配置
     * @param string|int $config_key
     * @param int        $zone_id 区域ID
     * @return string
     */
    public function createConfig($config_key, $zone_id = 0)
    {
        return $this->httpPost($this->serverMark . '/createConfig', ['config_key' => $config_key, 'zone_id' => $zone_id]);
    }

    /**
     * 获取拉新配置
     * @return string
     */
    public function getConfig()
    {
        return $this->httpPost($this->serverMark . '/getConfig');
    }

    /**
     * 绑定用户信息
     * @param array $user       邀请人用户信息(父级) ['user_id', 'name']
     * @param array $child_user 被邀请人用户信息（子级） ['user_id', 'name', 'position']
     * @return string
     */
    public function bindUser($user, $child_user)
    {
        return $this->httpPost($this->serverMark . '/bindUser', [
            'user'       => $user,
            'child_user' => $child_user,
        ]);
    }

    /**
     * 拉新用户列表
     * @param array $query 查询条件
     * @return string
     */
    public function userList(array $query = [])
    {
        return $this->httpPost($this->serverMark . '/userList', $query);
    }

    /**
     * 拉新用户详情
     * @param int $user_id 用户ID
     * @return string
     */
    public function userDetail(int $user_id)
    {
        return $this->httpPost($this->serverMark . '/userDetail', ['user_id' => $user_id]);
    }

    /**
     * 拉新用户团队信息（仅获取直属下级）
     * @param int $user_id 用户ID
     * @return string
     */
    public function userTeam(int $user_id)
    {
        return $this->httpPost($this->serverMark . '/userTeam', ['user_id' => $user_id]);
    }

    /**
     * 订单完成
     * @param int   $user_id 用户ID
     * @param array $order   订单信息['order_id', 'order_no', 'order_amount']
     * @return string
     */
    public function orderConfirm($user_id, $order)
    {
        return $this->httpPost($this->serverMark . '/orderConfirm', [
            'user_id' => $user_id,
            'order'   => $order,
        ]);
    }

    /**
     * 拉新订单佣金取消
     * @param int $user_id  用户ID
     * @param int $order_id 订单ID
     * @return string
     */
    // public function orderRefund(int $user_id, int $order_id)
    // {
    //     return $this->httpPost($this->serverMark . '/orderRefund', [
    //         'user_id'  => $user_id,
    //         'order_id' => $order_id,
    //     ]);
    // }

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
     * @param int $third_exchange_id 兑换信息ID
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
     * @param int    $third_exchange_id 兑换信息ID
     * @param int    $quantity          兑换数量
     * @param string $remark            说明
     * @return string
     */
    public function exchange(int $user_id, int $third_exchange_id, int $quantity, string $remark = '')
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
}
