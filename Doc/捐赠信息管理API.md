Made by Kurozaki

tags:Xianlaiyizhuan

``baseurl: http://139.199.195.54/Xianlaiyizhuan``
#捐赠信息管理API


###发布捐赠信息###

``url``
Home/Donation/createDonationInfo

**提交参数**
``Post提交，需要超级管理员权限``

字段|描述|是否必须|类型
-|-|-|-|-
intro|捐赠介绍|Y|VARCHAR|
publisher|主办机构|Y|VARCHAR|
addr|捐赠地址|Y|VARCHAR

**返回结果**

```
//成功
{
    "code": 20000,
    "response": {
        "intro": "捐书啦",
        "publisher": "wyu",
        "addr": "wyu",
        "ctime": 1492313559,
        "has_comm": 0,
        "likec": 0,
        "id": 2
    }
}

//失败
{
    "code":40000,
    "response":"Failed to create"
}
```

###修改捐赠信息###

``url``
Home/Donation/updateDonationInfo

**提交参数**
``Post提交，需要超级管理员权限``

字段|描述|是否必须|类型
-|-|-|-|-
intro|捐赠介绍|N|VARCHAR|
publisher|主办机构|N|VARCHAR|
addr|捐赠地址|N|VARCHAR

至少提交一个字段

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
    "response":"Update failed"
}
```

###删除捐赠信息###

``url``
Home/Donation/deleteDonationInfo

**提交参数**
``Post提交，需要超级管理员权限``

字段|描述|是否必须|类型
-|-|-|-|-
del_id|捐赠信息id|Y|INT|


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
    "response":"Delete failed"
}
```


###获取捐赠信息列表###

``url``
Home/Donation/getDonationList

**提交参数**
``Post提交，需要超级管理员权限``

字段|描述|是否必须|类型
-|-|-|-|-
intro|捐赠介绍|Y|VARCHAR|
publisher|主办机构|Y|VARCHAR|
addr|捐赠地址|Y|VARCHAR

**返回结果**

```
//成功
{
    "code": 20000,
    "response": [
        {
            "id": "2",
            "intro": "捐书啦",
            "publisher": "wyu",
            "addr": "wyu",
            "ctime": "1492313559",
            "has_comm": "0",
            "likec": "0"
        },
        {
            "id": "3",
            "intro": "捐书啦hahah",
            "publisher": "wyu",
            "addr": "wyu",
            "ctime": "1492313856",
            "has_comm": "0",
            "likec": "0"
        }
    ]
}
```