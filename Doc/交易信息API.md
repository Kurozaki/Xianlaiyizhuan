Made by Kurozaki

tags:Xianlaiyizhuan

``baseurl: http://139.199.195.54/Xianlaiyizhuan``

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
free|是否免费|Y|BOOL|非0免费，否则免费
picstr|描述图片(1-5张)|Y|VARCHAR|base64编码图片，多张用","分隔

**返回结果**

```
//成功
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

//失败
{
    "code": 40000,
    "response": "Failed to create."
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
free|是否免费|N|VARCHAR


**返回结果**

```
//成功
{
    "code":20000,
    "response":"Update success"
}

//失败
{
    "code": 40000,
    "response": "Update failed"
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

//失败
{
    "code": 40000,
    "response": "Failed to delete."
}
```



***

###修改交易信息介绍图片###

``url``
Home/Transact/editTransactionIntroPics

``Post提交，需要登录``

字段|描述|是否必须|类型
-|-|-|-|-
tr_id|信息id|Y|VARCHAR
op_str|操作串|Y|VARCHAR
pics_str|图片的base64|Y|TEXT


特别说明：
1、op_str: 用d/u+i的形式操作图片，d表示删除，u表示更新，i表示操作图片的索引，多步操作用"-"连接，如d1-u2-u4-d0
2、pics_str: 用base64编码的图片，多图用","分割


**返回结果**

```
{
    "code":20000,
    "response": [修改后图片的url]
}

//失败
{
    "code": 40000,
    "response": "Failed to update."
}
```

***

###将交易信息置为已售###

``url``
Home/Transact/setToSoldStatus

**提交参数**
``Post提交，需要登录``

字段|描述|是否必须|类型
-|-|-|-|-
t_id|交易信息id|Y|VARCHAR


**返回结果**

```
{
    "code":20000,
    "response":"Operate success"
}
```
***
###获取我发布的交易信息列表###

``url``
Home/Transact/getMyTransactionList

**提交参数**
``post，需要登录``
字段|描述|是否必须|类型
-|-|-|-|-
free|是否免费|N|VARCHAR
free 为 true 只获取免费列表，
false 只获取非免费列表，
不提交则获取全部。

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
free|是否免费|N|VARCHAR
free 为 true 只获取免费列表，
false 只获取非免费列表，
不提交则获取全部。


**返回结果**

```
//成功1
{
    "code":20000,
    "response":[json格式的多条交易信息列表]
}

//成功2
{
    "code": 20000,
    "response": false    //未找到结果
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

//失败
{
    "code": 40000,
    "response": "Failed"
}
```

***