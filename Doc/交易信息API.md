tags:Xianlaiyizhuan

#交易信息API


###发布交易信息###

``url``
Home/Transact/createTransaction

**提交参数**
``Post提交，需要登录``

字段|描述|是否必须|类型|备注
-|-|-|-|-
intro|物品介绍|Y|VARCHAR
type|类型|Y|VARCHAR|
price|价格|Y|VARCHAR|
*|描述图片|Y|FILE|最多5张图片

**返回结果**

```
{
    "code": 20000,
    "response": {
        "id": "1",
        "intro": "This is intro",
        "pics": "transact/transact_intro/2017-03-21/58d0ddc0c6dca.jpg",
        "type": "book",
        "price": "12",
        "likec": null,
        "ctime": "1490081945",
        "sell": "0",
        "seller_id": "1"
    }
}
```

***


###更新交易信息###

``url``
Home/Transact/updateTransactionInfo

**提交参数**
``Post提交，是否必须为N中至少提交一项，需要登录``

字段|描述|是否必须|类型
-|-|-|-|-
update_id|id|Y|INT
intro|介绍|N|VARCHAR
type|种类|N|VARCHAR
price|价格|N|VARCHAR


**返回结果**

```
{
    "code":20000,
    "response":"Update success"
}
```

***
###删除交易信息###

``url``
Home/Transact/deleteTransaction

**提交参数**
``Post提交，需要登录``

字段|描述|是否必须|类型
-|-|-|-|-
del_id|删除id|Y|VARCHAR


**返回结果**

```
{
    "code":20000,
    "response":"Delete success"
}
```

***

###修改交易信息介绍图片###

``url``
Home/Transact/editTransactionIntroPics

**提交参数**
``Post提交，需要登录``

字段|描述|是否必须|类型
-|-|-|-|-
update_id|删除id|Y|VARCHAR
op_str|操作字段|Y|VARCHAR

操作字段格式：d,u+k，k为图片url的id索引，用-连接可多步操作
d代表删除，u代表更新
例如，有3张图片，提交d0-u1-u3
其含义为删除第0张图片，替换第1张图片，添加一张图片（3大于已存在图片的索引，认为是添加，如果有d3则不会执行任何操作）

**返回结果**

```
{
    "code":20000,
    "response": [修改后图片的url]
}
```

***

###获取我发布的交易信息列表###

``url``
Home/Transact/getMyTransactionList

**提交参数**
``需要登录``
(无)


**返回结果**

```
{
    "code":20000,
    "response":[json格式的多条交易信息列表]
}
```

***
###获取指定用户的交易信息列表###

``url``
Home/Transact/specifyUserTransactionList


**提交参数**
``Post提交，需要登录``

字段|描述|是否必须|类型
-|-|-|-|-
seller_id|指定用户的id|Y|INT|


**返回结果**

```
{
    "code":20000,
    "response":[json格式的多条交易信息列表]
}
```

***
###获取最近发布的交易信息列表###

``url``
Home/Transact/getRecentTransactionList


**提交参数**
(无)



**返回结果**

```
{
    "code":20000,
    "response":[json格式的多条交易信息列表]
}
```

###点赞###

``url``
Home/Transact/giveLikeToTransaction


**提交参数**
``Post提交，需要登录``

字段|描述|是否必须|类型
-|-|-|-|-
tid|交易信息id|Y|INT|

**返回结果**

```
{
    "code":20000,
    "response":3      //当前赞的数量
}
```
