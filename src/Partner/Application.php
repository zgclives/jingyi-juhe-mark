<?php

namespace JuheMark\Partner;

use JuheMark\BasicService\BaseConfig;
use JuheMark\BasicService\RequestContainer;
use JuheMark\Kernel\Response;

/**
 * 分销功能
 */
class Application extends RequestContainer
{
    use Response;
    use BaseConfig;

    // 服务标识
    private $serverMark = 'partner';

    public function __construct(array $config = [], array $prepends = [], string $id = null)
    {
        parent::__construct($this->initConfig($config, $this->serverMark), $this->serverMark, true);
    }

    /**
     * 初始化分销配置
     * @param string|int $config_key
     * @param int        $zone_id 区域ID
     * @return string
     */
    public function createConfig($config_key, $zone_id = 0)
    {
        return $this->httpPost($this->serverMark . '/createConfig', ['config_key' => $config_key, 'zone_id' => $zone_id]);
    }

    /**
     * 获取分销配置
     * @return string
     */
    public function getConfig()
    {
        return $this->httpPost($this->serverMark . '/getConfig');
    }

    /**
     * 绑定用户信息
     * @param array  $user       用户信息['user_id', 'name', 'avatar']
     * @param array  $child_user 子级用户信息['user_id', 'name', 'avatar']
     * @param string $from       来源：register=注册，login=登录
     * @return string
     */
    public function bindUser($user, $child_user, $from = 'register')
    {
        return $this->httpPost($this->serverMark . '/bindUser', [
            'user'       => $user,
            'child_user' => $child_user,
            'from'       => $from,
        ]);
    }

    /**
     * 设置用户等级
     * @param array $user  用户信息['user_id', 'name']
     * @param int   $level 跳转等级
     * @return string
     */
    public function setUserLevel($user, $level)
    {
        return $this->httpPost($this->serverMark . '/setUserLevel', [
            'user'  => $user,
            'level' => $level,
        ]);
    }

    /**
     * 分销商用户列表
     * @param array $query 查询条件
     * @return string
     */
    public function userList(array $query = [])
    {
        return $this->httpPost($this->serverMark . '/userList', $query);
    }

    /**
     * 分销商用户详情
     * @param array $user 用户信息['user_id', 'name', 'avatar']
     * @return string
     */
    public function userDetail($user)
    {
        return $this->httpPost($this->serverMark . '/userDetail', ['user' => $user]);
    }

    /**
     * 分销商用户团队信息（仅获取直属下级）
     * @param int $user_id 用户ID
     * @return string
     */
    public function userTeam(int $user_id)
    {
        return $this->httpPost($this->serverMark . '/userTeam', ['user_id' => $user_id]);
    }

    /**
     * 用户统计报表
     * @param int   $user_id 用户ID
     * @param array $query   查询条件
     * @return string
     */
    public function userReport(int $user_id, array $query = [])
    {
        $query['user_id'] = $user_id;
        return $this->httpPost($this->serverMark . '/userReport', $query);
    }

    /**
     * 用户收益订单列表
     * @param int   $user_id 用户ID
     * @param array $query   查询条件
     * @return string
     */
    public function incomeOrderList(int $user_id, array $query = [])
    {
        $query['user_id'] = $user_id;
        return $this->httpPost($this->serverMark . '/incomeOrderList', $query);
    }

    /**
     * 订单支付，记录订单、佣金明细
     * @param array $user  用户信息['user_id', 'name', 'avatar']
     * @param array $order 订单信息['order_id', 'order_no', 'order_amount']
     * @return string
     */
    public function orderPay($user, $order)
    {
        return $this->httpPost($this->serverMark . '/orderPay', [
            'user'  => $user,
            'order' => $order,
        ]);
    }

    /**
     * 分销订单佣金确认
     * @param int    $user_id  用户ID
     * @param int    $order_id 订单ID
     * @param string $type     订单类型：RUN=跑腿单，ORDER=外卖单
     * @return string
     */
    public function orderConfirm(int $user_id, int $order_id, string $type = 'ORDER')
    {
        return $this->httpPost($this->serverMark . '/orderConfirm', [
            'user_id'    => $user_id,
            'order_id'   => $order_id,
            'order_type' => $type,
        ]);
    }

    /**
     * 分销订单佣金取消
     * @param int    $user_id  用户ID
     * @param int    $order_id 订单ID
     * @param string $type     订单类型：RUN=跑腿单，ORDER=外卖单
     * @return string
     */
    public function orderRefund(int $user_id, int $order_id, string $type = 'ORDER')
    {
        return $this->httpPost($this->serverMark . '/orderRefund', [
            'user_id'    => $user_id,
            'order_id'   => $order_id,
            'order_type' => $type,
        ]);
    }

    /**
     * 用户提现
     * @param int    $user_id        用户ID
     * @param int    $amount         操作金额（单位：厘）
     * @param string $remark         操作说明
     * @param string $alipay_account 支付宝账户
     * @param string $real_name      真实姓名
     * @return string
     */
    public function withdraw(int $user_id, int $amount, string $remark, string $alipay_account = '', string $real_name = '')
    {
        return $this->httpPost($this->serverMark . '/withdraw', [
            'user_id'        => $user_id,
            'amount'         => $amount,
            'remark'         => $remark,
            'alipay_account' => $alipay_account,
            'real_name'      => $real_name,
        ]);
    }

    /**
     * 用户提现记录
     * @param int   $user_id 用户ID
     * @param array $query   查询条件
     * @return string
     */
    public function withdrawLogs(int $user_id, array $query = [])
    {
        $query['user_id'] = $user_id;
        return $this->httpPost($this->serverMark . '/withdrawLogs', $query);
    }

    /**
     * 用户资金记录列表
     * @param int   $user_id 用户ID
     * @param array $query   查询条件
     * @return string
     */
    public function userBalanceChangeList(int $user_id, array $query = [])
    {
        $query['user_id'] = $user_id;
        return $this->httpPost($this->serverMark . '/userBalanceChangeList', $query);
    }

    /**
     * 操作分销用户佣金
     * @param int    $user_id 用户ID
     * @param int    $amount  操作金额（单位：厘）
     * @param string $kind    操作方式：INC=增加,DEC=扣减
     * @param string $remark  操作说明
     * @return string
     */
    public function commissionOperate(int $user_id, int $amount, string $kind, string $remark)
    {
        return $this->httpPost($this->serverMark . '/commissionOperate', [
            'user_id' => $user_id,
            'amount'  => $amount,
            'kind'    => $kind,
            'remark'  => $remark,
        ]);
    }
}
