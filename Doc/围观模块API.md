Made by Kurozaki

tags:Xianlaiyizhuan

``baseurl: http://139.199.195.54/Xianlaiyizhuan``
#围观模块API


###发布讨论话题###

``url``
Home/Rubberneck/createTopic

**提交参数**
``Post提交，需要登录``

字段|描述|是否必须|类型
-|-|-|-|-
title|话题标题|Y|VARCHAR
content|话题内容|Y|TEXT|



**返回结果**

```
//成功
{
    "code": 20000,
    "response": {
        "title": "这是标题",
        "content": "这是内容",
        "author_id": "2",
        "ctime": 1492399013,
        "has_comm": 0,
        "id": 4
    }
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
title|话题标题|N|VARCHAR
content|话题内容|N|TEXT|

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
            "id": "2",
            "author_id": "2",
            "title": "This is titlesss",
            "content": "Content222",
            "ctime": "1492350727",
            "has_comm": "0"
        },
        {
            "id": "4",
            "author_id": "2",
            "title": "这是标题",
            "content": "这是内容",
            "ctime": "1492399013",
            "has_comm": "0"
        }
    ]
}

//失败
{
    "code":40000,
    "response":"Failed"
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
            "id": "2",
            "author_id": "2",
            "title": "This is titlesss",
            "content": "Content222",
            "ctime": "1492350727",
            "has_comm": "0"
        },
        {
            "id": "4",
            "author_id": "2",
            "title": "这是标题",
            "content": "这是内容",
            "ctime": "1492399013",
            "has_comm": "0"
        }
    ]
}

//失败
{
    "code":40000,
    "response":"Failed"
}
```
***

###获取最近发布的话题###
``url``
Home/Rubberneck/getRecentTopicList


**提交参数**
(无)

```
//成功
{
    "code": 20000,
    "response": [
        {
            "id": "2",
            "author_id": "2",
            "title": "This is titlesss",
            "content": "Content222",
            "ctime": "1492350727",
            "has_comm": "0"
        },
        {
            "id": "4",
            "author_id": "2",
            "title": "这是标题",
            "content": "这是内容",
            "ctime": "1492399013",
            "has_comm": "0"
        }
    ]
}

//失败
{
    "code":40000,
    "response":"Failed"
}
```
***


