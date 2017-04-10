Made by Kurozaki

tags:Xianlaiyizhuan

``baseurl: http://139.199.195.54/Xianlaiyizhuan``
#线上支付API


###创建订单###

``url``
Home/Transact/createOrder

**提交参数**
``Post提交``

字段|描述|是否必须|类型
-|-|-|-|-
pay_pwd|支付密码|Y|INT
tr_id|交易信息id|Y|INT|


**返回结果**

```
//成功
{
    "code":20000,
    "response":"Create order success"
}


//失败
{
    "code":40000,
    "response":"Error pay password!"
}
```