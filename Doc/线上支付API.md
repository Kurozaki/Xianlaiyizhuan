Made by Kurozaki

tags:Xianlaiyizhuan

``baseurl: http://139.199.195.54/Xianlaiyizhuan``
#线上支付API


###创建订单###

``url``
Home/Transact/createOrder

**提交参数**
``Post提交，需要登录``

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



###完成订单###

``url``
Home/Transact/createOrder

**提交参数**
``Post提交，需要超级管理员权限``

字段|描述|是否必须|类型
-|-|-|-|-
order_id|订单id|Y|INT|


**返回结果**

```
//成功
{
    "code":20000,
    "response":"success"
}


//失败
{
    "code":40000,
    "response":"password!"
}
```

###订单列表###

``url``
Home/Transact/createOrder

**提交参数**
``Post提交，需要超级管理员权限``
(无)



**返回结果**

```
//成功
{
    "code": 20000,
    "response": [
        {
            "id": "2",
            "t_id": "1",
            "buyer": "2",
            "seller": "1",
            "price": "12",
            "status": "0"
        },
        {
            "id": "3",
            "t_id": "2",
            "buyer": "2",
            "seller": "1",
            "price": "100",
            "status": "0"
        },
        {
            "id": "6",
            "t_id": "3",
            "buyer": "2",
            "seller": "1",
            "price": "1",
            "status": "0"
        }
    ]
}


//失败
{
    "code": 40000,
    "response": "No permission"
}
```

