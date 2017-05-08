Made by Kurozaki

tags:Xianlaiyizhuan

``baseurl: http://139.199.195.54/Xianlaiyizhuan``
#捐赠API


###发布捐赠信息###

``url``
Home/Donation/createDonationInfo

**提交参数**
``Post提交，需要超级管理员权限``

字段|描述|是否必须|类型
-|-|-|-|-
ac_title|标题|Y|VARCHAR|
ac_time|活动时间|Y|VARCHAR|
ac_addr|活动地址|Y|VARCHAR
ac_pay|活动花费|Y|FLOAT
ac_chk_addr|核实地址|Y|VARCHAR|
ac_detail|活动详情|Y|VARCHAR|
ac_host|主办方|Y|VARCHAR|
ac_contact|联系方式|Y|VARCHAR|
(任意)|配图（单张）|Y|FILE

**返回结果**

```
//成功
{
    "code": 20000,
    "response": {
        "ac_title": "捐东西啦啦啦",
        "ac_time": "2017-04-08",
        "ac_addr": "五邑大学",
        "ac_pay": "233",
        "ac_chk_addr": "南主楼",
        "ac_detail": "详情",
        "ac_host": "你爸爸我",
        "ac_contact": "12345",
        "ctime": 1492610409,
        "has_comm": 0,
        "likec": 0,
        "ac_pic": "Public/donation/donation_info/2017-04-19/58f76d69de2d6.jpg",
        "id": 11
    }
}

//失败
{
    "code":40000,
    "response":"Failed to create"
}
```
***
###修改捐赠信息###

``url``
Home/Donation/updateDonationInfo

**提交参数**
``Post提交，需要超级管理员权限``

字段|描述|是否必须|类型
-|-|-|-|-
dn_id|捐赠信息id|Y|INT
ac_title|标题|N|VARCHAR|
ac_time|活动时间|N|VARCHAR|
ac_addr|活动地址|N|VARCHAR
ac_pay|活动花费|N|FLOAT
ac_chk_addr|核实地址|N|VARCHAR|
ac_detail|活动详情|N|VARCHAR|
ac_host|主办方|N|VARCHAR|
ac_contact|联系方式|N|VARCHAR|

除dn_id外，至少提交一个字段

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
***

###修改捐赠图片###

``url``
Home/Donation/deleteDonationInfo

**提交参数**
``Post提交，需要超级管理员权限``

字段|描述|是否必须|类型
-|-|-|-|-
dn_id|捐赠信息id|Y|INT|
(任意)|图片|Y|FILE


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
***

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
***



###获取捐赠信息列表（分页）###

``url``
Home/Donation/getAllDonationList

**提交参数**
``Post提交``
字段|描述|是否必须|类型|备注
-|-|-|-|-
offset|分页偏移量|N|INT|不提交默认为0

**返回结果**
```
{
    "code": 20000,
    "response": {
        "offset": 3,    //分页偏移量
        "data": [
            {
                "id": "8",
                "ac_title": "捐书啦",
                "ac_time": "2017-04-08",
                "ac_addr": "wyu",
                "ac_pay": "200",
                "ac_chk_addr": "wyu",
                "ac_detail": "detail",
                "ac_host": "wyu",
                "ac_contact": "1234",
                "ac_pic": "http://139.199.195.54/xianlaiyizhuan/Public/donation/donation_info/2017-04-19/58f76b9a80651.jpg",
                "ctime": "1492580176",
                "has_comm": "0",
                "likec": "0"
            },
            {
                "id": "11",
                "ac_title": "捐东西啦啦啦",
                "ac_time": "2017-04-08",
                "ac_addr": "五邑大学",
                "ac_pay": "233",
                "ac_chk_addr": "南主楼",
                "ac_detail": "详情",
                "ac_host": "你爸爸我",
                "ac_contact": "12345",
                "ac_pic": "http://139.199.195.54/xianlaiyizhuan/Public/donation/donation_info/2017-04-19/58f76d69de2d6.jpg",
                "ctime": "1492610409",
                "has_comm": "0",
                "likec": "0"
            }
            ...
        ]
    }
}

//失败
{
    "code": 40002,
    "response": null
}
```
***
###点赞###

``url``
Home/Donation/giveLikeToDonation


**提交参数**
``Post提交，需要登录``

字段|描述|是否必须|类型
-|-|-|-|-
dn_id|捐赠信息id|Y|INT|

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