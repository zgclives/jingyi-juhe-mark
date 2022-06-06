# 精易聚合营销模块

## 说明

集成常用的营销模块

## 安装(Installation)

```shell
composer require zgclives/jingyi-juhe-mark
```

## 功能(Features)

| 营销模块 | 实现                 | 是否完成 |
|------|--------------------| -------- |
| 分销功能 | Factory::Partner() | ☑️       |
| 拉新活动 | Factory::Invite()  | ☑️       |

## 使用(Usage)

### 分销功能

实例：

```php
use JuheMark\Factory;

/**
 * 聚合营销模块 - 分销功能
 */
class MarketPartnerService
{
    /**
     * 工厂模型 - 分销应用
     * @var \JuheMark\Partner\Application
     */
    protected $_app;

    /**
     * 分销配置key
     * @var string
     */
    protected $_config_key;

    /** 无分销等级 */
    const LEVEL_0 = 0;
    /** 分销等级1 */
    const LEVEL_1 = 1;
    /** 分销等级2 */
    const LEVEL_2 = 2;
    /** 分销等级3 */
    const LEVEL_3 = 3;

    /** 金额增加 */
    const KIND_INC = 'INC';
    /** 金额减少 */
    const KIND_DEC = 'DEC';

    /**
     * 构造函数
     * @param string|int $config_key 代理标记
     * @param int        $zone_id    区域ID
     */
    public function __construct($config_key, $zone_id = 0)
    {
        $this->_config_key = $config_key;
        $this->_app        = Factory::Partner([
            'config_key' => $config_key,
            'zone_id'    => $zone_id,
        ]);
    }

    /**
     * 统一返回结果
     * @param $response
     * @param $code
     * @return mixed
     */
    public function result($response, &$code = 0)
    {
        if (!$response || !is_array($response)) {
            fail('请求结果错误');
        }

        $code = ($response['code'] ?? 0);
        if ($code != 1) {
            fail($code . '：' . ($response['msg'] ?? ''));
        }

        return $response['data'];
    }

    /**
     * 创建分销配置
     * @param string|int $config_key 分销配置key
     * @return string
     */
    public function createConfig($config_key)
    {
        return $this->_app->createConfig($config_key);
        return $this->result($this->_app->createConfig($config_key));
    }

    /**
     * 获取分销配置
     * @return string
     */
    public function getConfig()
    {
        return $this->result($this->_app->getConfig());
    }

    /**
     * 绑定用户信息
     * @param array $user       用户信息['user_id', 'name']
     * @param array $child_user 子级用户信息['user_id', 'name']
     * @return string
     */
    public function bindUser($user, $childUser)
    {
        $response = $this->_app->bindUser(
            [
                'user_id' => $user['user_id'],
                'name'    => $user['name'],
                'avatar'  => $user['avatar'] ?? '',
            ],
            [
                'user_id' => $childUser['user_id'],
                'name'    => $childUser['name'],
                'avatar'  => $childUser['avatar'] ?? '',
            ]
        );
        return $response;
        // return $this->result($response);
    }

    /**
     * 设置用户等级
     * @param array $user  用户信息['user_id', 'name']
     * @param int   $level 跳转等级
     * @return string
     */
    public function setUserLevel($user, $level)
    {
        $response = $this->_app->setUserLevel(
            [
                'user_id' => $user['uid'],
                'name'    => $user['nick_name'],
                'avatar'  => $user['avatar_url'] ?? '',
            ],
            $level
        );
        return $this->result($response);
    }

    /**
     * 分销商用户列表
     * @param array $query 查询条件
     * @return string
     */
    public function userList(array $query = [])
    {
        return $this->result($this->_app->userList($query));
    }

    /**
     * 分销商用户详情
     * @param array $user 用户信息['user_id', 'name', 'avatar']
     * @return string
     */
    public function userDetail($user)
    {
        return $this->result($this->_app->userDetail($user));
    }

    /**
     * 分销商用户团队信息（仅获取直属下级）
     * @param int $user_id 用户ID
     * @return string
     */
    public function userTeam(int $user_id)
    {
        return $this->result($this->_app->userTeam($user_id));
    }

    /**
     * 订单支付，记录订单、佣金明细
     * @param array $user  用户信息['user_id', 'name']
     * @param array $order 订单信息['order_id', 'order_no', 'order_amount']
     * @return string
     */
    public function orderPay($user, $order, $type = 'ORDER')
    {
        return $this->_app->orderPay(
            [
                'user_id' => $user['uid'],
                'name'    => $user['nick_name'],
                'avatar'  => $user['avatar_url'] ?? '',
            ],
            [
                'order_id'     => $order['id'],
                'order_no'     => $order['order_id'],
                'order_amount' => $order['actual'] * 1000,
                'order_type'   => $type
            ]
        );
    }

    /**
     * 分销订单佣金确认
     * @param int $user_id  用户ID
     * @param int $order_id 订单ID
     * @return string
     */
    public function orderConfirm(int $user_id, int $order_id)
    {
        return $this->_app->orderConfirm($user_id, $order_id);
        // return $this->result($this->_app->orderConfirm($user_id, $order_id));
    }

    /**
     * 分销订单佣金取消
     * @param int $user_id  用户ID
     * @param int $order_id 订单ID
     * @return string
     */
    public function orderRefund(int $user_id, int $order_id)
    {
        return $this->_app->orderRefund($user_id, $order_id);
        // return $this->result($this->_app->orderRefund($user_id, $order_id));
    }

    /**
     * 用户统计报表
     * @param int   $user_id 用户ID
     * @param array $query   查询条件
     * @return string
     */
    public function userReport(int $user_id, array $query = [])
    {
        return $this->_app->userReport($user_id, $query);
        // return $this->result($this->_app->userReport($user_id, $query));
    }

    /**
     * 用户收益订单列表
     * @param int   $user_id 用户ID
     * @param array $query   查询条件
     * @return string
     */
    public function incomeOrderList(int $user_id, array $query = [])
    {
        return $this->result($this->_app->incomeOrderList($user_id, $query));
    }

    /**
     * 用户提现
     * @param int    $user_id 用户ID
     * @param int    $amount  操作金额（单位：厘）
     * @param string $remark  操作说明
     * @return string
     */
    public function withdraw(int $user_id, int $amount, string $remark)
    {
        return $this->result($this->_app->withdraw($user_id, $amount, $remark));
    }

    /**
     * 用户提现记录
     * @param int   $user_id 用户ID
     * @param array $query   查询条件
     * @return string
     */
    public function withdrawLogs(int $user_id, array $query = [])
    {
        return $this->result($this->_app->withdrawLogs($user_id, $query));
    }

    /**
     * 用户提现记录
     * @param int   $user_id 用户ID
     * @param array $query   查询条件
     * @return string
     */
    public function userBalanceChangeList(int $user_id, array $query = [])
    {
        return $this->result($this->_app->userBalanceChangeList($user_id, $query));
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
        return $this->result($this->_app->commissionOperate($user_id, $amount, $kind, $remark));
    }
}
```

### 拉新活动

示例代码：

```php
use JuheMark\Factory;

/**
 * 聚合营销模块 - 拉新活动
 */
class MarketInviteService
{
    /**
     * 工厂模型 - 拉新应用
     * @var \JuheMark\Invite\Application
     */
    protected $_app;

    /**
     * 拉新配置key
     * @var string
     */
    protected $_config_key;

    /**
     * 构造函数
     * @param string|int $config_key 代理标记
     * @param int        $zone_id    区域ID
     */
    public function __construct($config_key, $zone_id = 0)
    {
        $this->_config_key = $config_key;
        $this->_app        = Factory::Invite([
            'config_key' => $config_key,
            'zone_id'    => $zone_id,
        ]);
    }

    /**
     * 统一返回结果
     * @param $response
     * @param $code
     * @return mixed
     */
    public function result($response, &$code = 0)
    {
        if (!$response || !is_array($response)) {
            fail('请求结果错误');
        }

        $code = ($response['code'] ?? 0);
        if ($code != 1) {
            fail($code . '：' . ($response['msg'] ?? ''));
        }

        return $response['data'];
    }

    /**
     * 创建拉新配置
     * @param string|int $config_key 拉新配置key
     * @return string
     */
    public function createConfig($config_key)
    {
        return $this->_app->createConfig($config_key);
        return $this->result($this->_app->createConfig($config_key));
    }

    /**
     * 获取拉新配置
     * @return string
     */
    public function getConfig()
    {
        return $this->result($this->_app->getConfig());
    }

    /**
     * 绑定用户信息
     * @param array $user       用户信息['user_id', 'name']
     * @param array $child_user 子级用户信息['user_id', 'name', 'position']
     * @return string
     */
    public function bindUser($user, $childUser)
    {
        $response = $this->_app->bindUser(
            [
                'user_id' => $user['user_id'],
                'name'    => $user['name'],
                'avatar'  => $user['avatar'] ?? '',
            ],
            [
                'user_id'  => $childUser['user_id'],
                'name'     => $childUser['name'],
                'avatar'   => $childUser['avatar'] ?? '',
                'position' => $childUser['position']
            ]
        );
        return $response;//$this->result($response);
    }

    /**
     * 拉新用户列表
     * @param array $query 查询条件
     * @return string
     */
    public function userList(array $query = [])
    {
        return $this->result($this->_app->userList($query));
    }

    /**
     * 拉新用户详情
     * @param int $user_id 用户ID
     * @return string
     */
    public function userDetail(int $user_id)
    {
        return $this->result($this->_app->userDetail($user_id));
    }

    /**
     * 拉新用户团队信息（仅获取直属下级）
     * @param int $user_id 用户ID
     * @return string
     */
    public function userTeam(int $user_id)
    {
        return $this->result($this->_app->userTeam($user_id));
    }

    /**
     * 订单完成
     * @param int   $user_id 用户ID
     * @param array $order   订单信息['order_id', 'order_no', 'order_amount']
     * @return string
     */
    public function orderConfirm($user_id, $order)
    {
        return $this->_app->orderConfirm(
            $user_id,
            [
                'order_id'     => $order['id'],
                'order_no'     => $order['long_id'],
                'order_amount' => $order['actual'],
            ]
        );
    }

    /**
     * 兑换信息列表
     * @param array $query 查询条件
     * @return string
     */
    public function exchangeInfoList(array $query = [])
    {
        return $this->result($this->_app->exchangeInfoList($query));
    }

    /**
     * 兑换信息详情
     * @param int $third_exchange_id 兑换信息ID
     * @return string
     */
    public function exchangeInfoDetail($third_exchange_id)
    {
        return $this->result($this->_app->exchangeInfoDetail($third_exchange_id));
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
        return $this->result($this->_app->exchange($user_id, $third_exchange_id, $quantity, $remark));
    }

    /**
     * 用户兑换记录
     * @param int   $user_id 兑换人ID
     * @param array $query   查询条件
     * @return string
     */
    public function userExchangeLogs(int $user_id, array $query = [])
    {
        return $this->result($this->_app->userExchangeLogs($user_id, $query));
    }

    /**
     * 用户兑换记录详情
     * @param int $user_id 兑换人ID
     * @param int $log_id  兑换记录ID
     * @return string
     */
    public function userExchangeLogDetail(int $user_id, int $exchange_log_id)
    {
        return $this->result($this->_app->userExchangeLogDetail($user_id, $exchange_log_id));
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
        return $this->result($this->_app->balanceOperate($user_id, $amount, $kind, $remark));
    }
}
```
