﻿Made by Kurozaki

tags:Xianlaiyizhuan

``baseurl: http://139.199.195.54/Xianlaiyizhuan``
#私信API

###发送私信###
``url``
Home/PMsg/sendPMsg

**提交数据**
``POST，需要登录``
字段|描述|是否必须|类型
-|-|-|-
receiver|接收方用户id|Y|INT
content|私信内容|Y|TEXT    

**返回内容**
```
{
    "code":20000,
    "response":{
        "id": 1,
        "type": 0,    //0用户消息，1系统消息
        "sender": 1,
        "receiver": 2,
        "content": "private message content!",
        "ctime": 1286934098,
        "status": 0,
        "mark": 0     //是否为已读
    }
}
```
***

###删除私信###
``url``
Home/PMsg/deletePMsg

**提交数据**
``POST，需要登录``
字段|描述|是否必须|类型
-|-|-|-
del_id|私信id|Y|INT

**返回内容**
```
{
    "code":20000,
    "response":"Delete success"
}
```

***
###获取私信列表###
``url``
Home/PMsg/getPMsgList

**提交数据**
``POST，需要登录``
字段|描述|是否必须|类型
-|-|-|-
m_type|私信类型|Y|VARCHAR
参数可用字段：
send: 自己发出去的消息
receive: 自己接收到的消息
sys: 系统消息


**返回内容**
```
{
    "code":20000,
    "response":[
        {
            "id": 1,
            "type": 0,    //0用户消息，1系统消息
            "sender": 1,
            "receiver": 2,
            "content": "private message content!",
            "ctime": 1286934098,
            "status": 0,
            "mark": 0
        },
        ...
    ]
}
```

***
###获取新私信条数###
``url``
Home/PMsg/getPMsgNotice

**提交数据**
``POST，需要登录``
(无)

**返回内容**
```

{
    "code":20000,
    "response": {
        "pm":"1"    //新私信的条数
    }
}
```

***
###将接收的私信标记为已读###
``url``
Home/PMsg/markRecPMsg

**提交数据**
``POST，需要登录``
字段|描述|是否必须|类型
-|-|-|-
mk_id|私信id|Y|INT

**返回内容**
```

{
    "code":20000,
    "response": "Mark success"
}
```