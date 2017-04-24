Made by Kurozaki

tags:Xianlaiyizhuan

``baseurl: http://139.199.195.54/Xianlaiyizhuan``

#物品求助（需求）API

###发布求助###
``url``
Home/Requirement/createRequirement

**提交数据**
``POST，需要登录``
字段|描述|是否必须|类型
-|-|-|-
intro|需要物品的描述|Y|TEXT
type|需要物品的类型|Y|VARCHAR
price|预计的价格，负数表任意|Y|FLOAT

**返回内容**
```
{
    "code": 20000,
    "response": {
        "intro": "I need an NDS",
        "type": "game",
        "price": "998",
        "req_user": "1",
        "ctime": 1490239702,
        "id": 16
    }
}
```
***

###修改求助内容###
``url``
Home/Requirement/updateRequirementInfo

**提交数据**
``POST，需要登录，至少提交一个非必须字段``
字段|描述|是否必须|类型
-|-|-|-
update_id|修改的id|Y|INT
intro|需要物品的描述|N|TEXT
type|需要物品的类型|N|VARCHAR
price|预计的价格，负数表任意|N|FLOAT

**返回内容**
```
{
    "code": 20000,
    "response": "Update success"
}
```
***

###删除求助###
``url``
Home/Requirement/deleteRequirement

**提交数据**
``POST，需要登录``
字段|描述|是否必须|类型
-|-|-|-
del_id|删除的id|Y|INT


**返回内容**
```
{
    "code": 20000,
    "response": "Delete success"
}
```
***

###我的求助列表###
``url``
Home/Requirement/myRequirementList

**提交数据**
``POST，需要登录``
(无)


**返回内容**
```
{
    "code": 20000,
    "response": [
        {
            "id": "16",
            "intro": "I need an NDS",
            "type": "game",
            "price": "998",
            "ctime": "1490239702",
            "req_user": "1"
        }
        ...        //所有需求信息
    ]
}
```
***

###求助置为解决状态###
``url``
Home/Requirement/setToSolvedStatus

**提交数据**
``POST，需要登录``
字段|描述|是否必须|类型
-|-|-|-
req_id|需求id|Y|INT


**返回内容**
```
{
    "code": 20000,
    "response": "Operate success"
}
```
***

###最新发布的求助信息###
``url``
Home/Requirement/recentRequirementList

**提交数据**
(无)


**返回内容**
```
{
    "code": 20000,
    "response": [
        {
            "id": "14",
            "intro": "Need a PHP book!",
            "type": "book",
            "price": "12",
            "ctime": "1490194573",
            "req_user": "1"
        },
        {
            "id": "16",
            "intro": "I need an NDS",
            "type": "game",
            "price": "998",
            "ctime": "1490239702",
            "req_user": "1"
        }
        ...        //至多保存20条
    ]
}
```
***
###求助信息列表（分页）###
``url``
Home/Requirement/getAllRequirementList

**提交数据**
``POST``
字段|描述|是否必须|类型
-|-|-|-
offset|分页偏移|N|INT


**返回内容**
```
{
    "code": 20000,
    "response": [
        {
            "id": "17",
            "intro": "1",
            "type": "0",
            "pics": null,
            "price": "1",
            "ctime": "1492575679",
            "solve": "0",
            "req_user": {
                "id": "6",
                "nickname": "3115003835",
                "avatar": null
            },
            "likec": "0",
            "has_comm": "0"
        },
        {
            "id": "20",
            "intro": "奶茶多少钱在线等",
            "type": "0",
            "pics": [
                "http://139.199.195.54/xianlaiyizhuan/Public/requirement/requirement_info/beb04c41b45927cf7e9f8fd4bb519e86.jpg",
                "http://139.199.195.54/xianlaiyizhuan/Public/requirement/requirement_info/39461a19e9eddfb385ea76b26521ea48.jpg"
            ],
            "price": "10",
            "ctime": "1492744686",
            "solve": "0",
            "req_user": {
                "id": "2",
                "nickname": "吉尔伽美什",
                "avatar": "http://139.199.195.54/xianlaiyizhuan/Public/user/user_avatar/2017-03-19/58ce79397e7f6.jpg"
            },
            "likec": "0",
            "has_comm": "0"
        },
        {
            "id": "21",
            "intro": "奶茶多少钱在线等",
            "type": "0",
            "pics": [
                "http://139.199.195.54/xianlaiyizhuan/Public/requirement/requirement_info/36b3b5f54143786b7ab2ebb6bcd06e75.jpg",
                "http://139.199.195.54/xianlaiyizhuan/Public/requirement/requirement_info/4efb80f630ccecb2d3b9b2087b0f9c89.jpg"
            ],
            "price": "10",
            "ctime": "1492744833",
            "solve": "0",
            "req_user": {
                "id": "2",
                "nickname": "吉尔伽美什",
                "avatar": "http://139.199.195.54/xianlaiyizhuan/Public/user/user_avatar/2017-03-19/58ce79397e7f6.jpg"
            },
            "likec": "0",
            "has_comm": "0"
        }
    ]
}
```