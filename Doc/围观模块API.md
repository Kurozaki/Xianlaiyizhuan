Made by Kurozaki

tags:Xianlaiyizhuan

``baseurl: http://139.199.195.54/Xianlaiyizhuan``
#围观模块API


###发布讨论话题###

``url``
Home/Rubberneck/createTopic

**提交参数**
``Post提交，需要登录``

字段|描述|是否必须|类型|备注
-|-|-|-|-
content|话题内容|Y|TEXT|
picstr|图片|Y|VARCHAR|base64编码，1-5张，逗号隔开


**返回结果**

```
//成功
{
    "id": "6",
    "author_id": "2",
    "content": "Call me father",
    "pics": [
        "Public/rubberneck/rubberneck_info/581558c2bf98874720c8e98262ea77bf.jpg",
        "Public/rubberneck/rubberneck_info/581558c2bf98874720c8e98262ea77bf.jpg"
    ],
    "ctime": "1492681358",
    "has_comm": "0"
}


//失败
{
    "code":40000,
    "response":"Failed to create topic"
}
```


***


###编辑自己发布的话题###
``url``
Home/Rubberneck/editTopic


**提交参数**
``Post提交，需要登录``

字段|描述|是否必须|类型
-|-|-|-|-
tp_id|话题id|Y|INT
content|话题内容|Y|TEXT|

**返回结果**

```
//成功
{
    "code":20000,
    "response":"Update success"
}

//失败
{
    "code":40000,
    "response":"Failed to update"
}
```
***


###删除话题###
``url``
Home/Rubberneck/deleteTopic


**提交参数**
``Post提交，需要登录``

字段|描述|是否必须|类型
-|-|-|-|-
del_id|话题id|Y|INT
**返回结果**

```
//成功
{
    "code":20000,
    "response":"Delete success"
}

//失败
{
    "code":40000,
    "response":"Failed to delete"
}
```
***

###获取自己发布的话题###
``url``
Home/Rubberneck/getMyTopicList


**提交参数**
``需要登录``
(无)

```
//成功
{
    "code": 20000,
    "response": [
        {
            "id": "6",
            "author_id": "2",
            "content": "Call me father",
            "pics": [
                "Public/rubberneck/rubberneck_info/581558c2bf98874720c8e98262ea77bf.jpg",
                "Public/rubberneck/rubberneck_info/581558c2bf98874720c8e98262ea77bf.jpg"
            ],
            "ctime": "1492681358",
            "has_comm": "0"
        }
    ]
}

//失败
{
    "code":40000,
    "response": null
}
```
***

###获取某用户发布的话题###
``url``
Home/Rubberneck/getUserTopicList

**提交参数**
``Post提交，需要登录``

字段|描述|是否必须|类型
-|-|-|-|-
user_id|话题id|Y|INT
**返回结果**
```
//成功
{
    "code": 20000,
    "response": [
        {
            "id": "6",
            "author_id": "2",
            "content": "Call me father",
            "pics": [
                "Public/rubberneck/rubberneck_info/581558c2bf98874720c8e98262ea77bf.jpg",
                "Public/rubberneck/rubberneck_info/581558c2bf98874720c8e98262ea77bf.jpg"
            ],
            "ctime": "1492681358",
            "has_comm": "0"
        }
    ]
}

//失败
{
    "code":40000,
    "response": null
}
```
***

###获取最近发布的话题###
``url``
Home/Rubberneck/getRecentTopicList


**提交参数**
(无)

**返回结果**
```
//成功
{
    "code": 20000,
    "response": [
        {
            "id": "5",
            "content": "Content",
            "pics": [
                "Public/rubberneck/rubberneck_info/3eb6116899911886ed4d82a36117d087.jpg"
            ],
            "ctime": "1492681204",
            "has_comm": "0",
            "author": {
                "id": "1",
                "nickname": "爸爸",
                "avatar": null
            }
        },
        {
            "id": "6",
            "content": "Call me father",
            "pics": [
                "Public/rubberneck/rubberneck_info/581558c2bf98874720c8e98262ea77bf.jpg",
                "Public/rubberneck/rubberneck_info/581558c2bf98874720c8e98262ea77bf.jpg"
            ],
            "ctime": "1492681358",
            "has_comm": "0",
            "author": {
                "id": "2",
                "nickname": "吉尔伽美什",
                "avatar": "http://139.199.195.54/xianlaiyizhuan/Public/user/user_avatar/2017-03-19/58ce79397e7f6.jpg"
            }
        }
    ]
}

//失败
{
    "code":40002,
    "response": null
}
```
***
###获取发布的话题（分页）###
``url``
Home/Rubberneck/getRecentTopicList


**提交参数**
字段|描述|是否必须|类型
-|-|-|-|-
offset|分页偏移量|N|INT

**返回结果**
```
//成功
{
    "code": 20000,
    "response": [
        {
            "id": "5",
            "author_id": {
                "id": "1",
                "nickname": "爸爸",
                "avatar": null
            },
            "content": "Content",
            "pics": [
                "http://139.199.195.54/xianlaiyizhuan/Public/rubberneck/rubberneck_info/3eb6116899911886ed4d82a36117d087.jpg"
            ],
            "ctime": "1492681204",
            "has_comm": "0",
            "likec": "0"
        },
        {
            "id": "6",
            "author_id": {
                "id": "2",
                "nickname": "吉尔伽美什",
                "avatar": "http://139.199.195.54/xianlaiyizhuan/Public/user/user_avatar/2017-03-19/58ce79397e7f6.jpg"
            },
            "content": "Call me father",
            "pics": [
                "http://139.199.195.54/xianlaiyizhuan/Public/rubberneck/rubberneck_info/581558c2bf98874720c8e98262ea77bf.jpg",
                "http://139.199.195.54/xianlaiyizhuan/Public/rubberneck/rubberneck_info/581558c2bf98874720c8e98262ea77bf.jpg"
            ],
            "ctime": "1492681358",
            "has_comm": "0",
            "likec": "0"
        }
    ]
}

//失败
{
    "code":40002,
    "response": null
}
```

***
###点赞###

``url``
Home/Rubberneck/giveLikeToTopic


**提交参数**
``Post提交，需要登录``

字段|描述|是否必须|类型
-|-|-|-|-
tp_id|围观话题id|Y|INT|

**返回结果**

```
//成功
{
    "code":20000,
    "response":{
        "likec": 3    //当前赞的数量
    }      
}

//失败
{
    "code": 40000,
    "response": "Failed"
}
```