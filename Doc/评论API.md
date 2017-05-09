Made by Kurozaki

tags:Xianlaiyizhuan

``baseurl: http://139.199.195.54/Xianlaiyizhuan``

#评论API



###发表评论###
``url``
Home/Comment/leaveComment


**提交参数**
``Post提交，需要登录``

字段|描述|是否必须|类型
-|-|-|-|-
type|发布信息类型|Y|INT
p_id|发布信息id|Y|INT|
content|评论内容|Y|VARCHAR

type可选
1 => 交易信息
2 => 求助信息
3 => 捐赠信息
4 => 围观话题

**返回结果**

```
{
    "code":20000,
    "response":{
        "id": "1",
        "type": "2"
        "p_id": "1",
        "user_id": "2",
        "content": "comment!",
        "ctime": "12",
        "likec": "0"
    }
}
```
***
###删除评论（只能删除自己发表的）###

``url``
Home/Comment/deleteComment


**提交参数**
``Post提交，需要登录``

字段|描述|是否必须|类型
-|-|-|-|-
del_id|评论的id|Y|INT|

**返回结果**
```
{
    "code": 20000,
    "response": "Delete success"
}
```

***
###我发表的评论###

``url``
Home/Comment/myCommentList


**提交参数**
``Post提交，需要登录``
(无)

**返回结果**
```
{
    "code": 20000,
    "response": [
        {
            "id": "1",
            "p_id": "2",
            "type": "1",
            "user_id": "1",
            "content": "content",
            "ctime": "1490837603",
            "likec": "0"
        },
        {
            "id": "3",
            "p_id": "13",
            "type": "2",
            "user_id": "1",
            "content": "content",
            "ctime": "1490837645",
            "likec": "0"
        }
        ...    //有多条自己发表的评论
    ]
}
```

***
###信息包含的评论###

``url``
Home/Comment/postCommentList


**提交参数**
``Post提交，需要登录``

字段|描述|是否必须|类型
-|-|-|-|-
type|信息类型|Y|INT
p_id|信息id|Y|INT|

type可选
1 => 交易信息
2 => 求助信息
3 => 捐赠信息

**返回结果**
```
{
    "code": 20000,
    "response": [
        {
            "id": "11",
            "p_id": "5",
            "type": "1",
            "content": "This is the comment",
            "ctime": "1492089484",
            "likec": "0",
            "author": {
                "id": "2",
                "nickname": "吉尔伽美什",
                "avatar": "http://139.199.195.54/xianlaiyizhuan/Public/user/user_avatar/2017-03-19/58ce79397e7f6.jpg"
            }
        },
        ...    //可能多条评论
    ]
}
```