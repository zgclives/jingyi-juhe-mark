<?php

namespace JuheMark\MonthCard;

use JuheMark\BasicService\BaseConfig;
use JuheMark\BasicService\RequestContainer;
use JuheMark\Kernel\Response;

/**
 * 会员月卡
 */
class Application extends RequestContainer
{
    use Response;
    use BaseConfig;

    // 服务标识
    private $serverMark = 'month_card';

    public function __construct(array $config = [], array $prepends = [], string $id = null)
    {
        parent::__construct($this->initConfig($config, $this->serverMark), $this->serverMark, true);
    }

    /**
     * 初始化月卡配置
     * @param string|int $config_key
     * @param int        $zone_id 区域ID
     * @return string
     */
    public function createConfig($config_key, $zone_id = 0)
    {
        return $this->httpPost($this->serverMark . '/createConfig', ['config_key' => $config_key, 'zone_id' => $zone_id]);
    }

    /**
     * 获取月卡配置
     * @param int $user_id 用户ID
     * @return string
     */
    public function getConfig($user_id = 0)
    {
        return $this->httpPost($this->serverMark . '/getConfig', [
            'user_id' => $user_id,
        ]);
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
     * 月卡用户详情
     * @param int $user_id 用户ID
     * @return string
     */
    public function userDetail(int $user_id)
    {
        return $this->httpPost($this->serverMark . '/userDetail', ['user_id' => $user_id]);
    }

    /**
     * 创建订单
     * @param array  $user    用户信息['user_id', 'name']
     * @param string $type    下单类型：MONTH_CARD=开通月卡,COUPON_CARD=卡券包
     * @param string $card_id 卡包ID
     * @return string
     */
    public function createOrder(array $user, string $type, string $card_id = '')
    {
        return $this->httpPost($this->serverMark . '/createOrder', [
            'user'    => $user,
            'type'    => $type,
            'card_id' => $card_id,
        ]);
    }

    /**
     * 订单支付回调
     * @param int   $user_id 用户ID
     * @param array $order   订单信息['order_id', 'order_no', 'order_amount']
     * @return array
     */
    public function orderPay($user_id, $order)
    {
        return $this->httpPost($this->serverMark . '/orderPay', [
            'user_id' => $user_id,
            'order'   => $order,
        ]);
    }
}
