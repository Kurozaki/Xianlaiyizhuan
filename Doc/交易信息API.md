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
type|类型|Y|INT|参见文档底部类型表
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
type|类型|Y|INT|
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

###获取交易信息列表（分页）###

``url``
Home/Transact/getRecentTransactionList
**提交参数**
``Post提交``

字段|描述|是否必须|类型|说明
-|-|-|-|-
offset|分页偏移量|N|INT|不提交默认为0
type|物品类型|N|INT|参见类型表


**返回结果**
```
//成功
{
    "code": 20000,
    "response": {
        "continue_load": 0    //代表能否继续加载分页
        "offset": 4,
        "data": [
            {
                "id": "21",
                "free": "0",
                "intro": "钢琴",
                "pics": [
                    "http://139.199.195.54/xianlaiyizhuan/Public/transact/transact_intro/ce73dfea68bf04162382f4181b6d785c.jpg"
                ],
                "type": "6",
                "price": "11111",
                "likec": "0",
                "ctime": "1494328441",
                "sell": "0",
                "has_comm": "1",
                "seller": {
                    "id": "2",
                    "nickname": "吉尔伽美什",
                    "avatar": "http://139.199.195.54/xianlaiyizhuan/Public/user/user_avatar/2017-03-19/58ce79397e7f6.jpg"
                }
            },
            ...    //返回多条数据    
        ]
    }
}

//失败（结果为空）
{
    "code": 40002,
    "response": null        
}
```

***


###类型表###
用于交易信息的type参数

提交值|含义
-|-
0|书籍
1|生活用品
2|体育用品
3|学习用品
4|手机
5|电脑
6|乐器
7|电子产品