Made by Kurozaki

tags:Xianlaiyizhuan

``baseurl: http://139.199.195.54/Xianlaiyizhuan``

#物品需求API

###提交需求###
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

###修改需求内容###
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

###删除需求###
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

###我的需求列表###
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

###需求置为解决状态###
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

###最新发布的需求###
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