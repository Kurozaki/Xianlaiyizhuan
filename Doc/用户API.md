tags:Xianlaiyizhuan

#用户API

``Base url: http://139.199.195.54/Xianlaiyizhuan``


###学生信息认证###

``url``
Home/User/idnCheck

**提交参数**
``Post提交``

字段|描述|是否必须|类型|备注
-|-|-|-|-
id_number|学号|Y|VARCHAR
password|子系统的登录密码|Y|VARCHAR|
realname|真实姓名|Y|VARCHAR|

**返回结果**

```
{
    "code":20000,
    "response":"Confirm success"
}
```


***


###用户注册###
``url``
Home/User/userRegister

**提交参数**
``Post提交，需要学生验证的session``

字段|描述|是否必须|类型|备注
-|-|-|-|-
tel|电话号码|Y|VARCHAR
password|账号登录密码|Y|VARCHAR|
nickname|昵称|N|VARCHAR|
addr|住址|N|VARCHAR|
sign|个性签名|N|VARCHAR|

**返回结果**

```
{
    "code":20000,
    "response":"Register success"
}
```
***


###用户登录###

``url``
Home/User/userLogin

**提交参数**
字段|描述|是否必须|类型|备注
-|-|-|-|-
id_number|学号（账号）|Y|VARCHAR
password|密码|Y|VARCHAR|

**返回结果**

```
{
    "code":20000,
    "response":"Login success"
}
```

###用户登出###

``url``
Home/User/userLogout

**提交参数**
``需要登录``
（无）

**返回结果**

```
{
    "code":20000,
    "response":"Logout success"
}
```
***

###获取用户信息###

``url``
Home/User/getUserInfo

**提交参数**
``需要登录``
字段|描述|是否必须|类型
-|-|-|-|-
srh_id|用户id|N|INT
（不提交则默认获取当前在线用户信息）


**返回结果**

```
{
    "code": 20000,
    "response": {
        "id": "1",
        "id_number": "3115002333",
        "realname": "Admin",
        "tel": "15113312798",
        "nickname": "坂田金时",
        "avatar": null,
        "addr": "Akina",
        "sign": null
    }
}

```
***

###查找用户###

``url``
Home/User/searchUser

**提交参数**
字段|描述|是否必须|类型|备注
-|-|-|-|-
key|查找key|Y|VARCHAR|查找key可以是id_number, realname, nickname
val|查找值|Y|VARCHAR


**返回结果**

```
{
    "code": 20000,
    "response": {
        "id": "1",
        "id_number": "3115002333",
        "realname": "Admin",
        "tel": "15113312798",
        "nickname": "坂田金时",
        "avatar": null,
        "addr": "Akina",
        "sign": null
    }
}

```
***

###更新密码###
``url``
Home/User/updatePassword

**提交参数**
``需要登录``
字段|描述|是否必须|类型
-|-|-|-|-
oldPwd|现用密码|Y|VARCHAR
newPwd|新密码|Y|VARCHAR


**返回结果**

```
{
    "code":20000,
    "response":"Update success"
}
```
***
###更新用户头像###
``url``
Home/User/updateAvatar

**提交参数**
``需要登录，提交一个图片文件，jpg或png``



**返回结果**

```
{
    "code":20000,
    "response":"Update success"
}
```

***
###更新用户信息###
``url``
Home/User/updateUserInfo

**提交参数**
``需要登录，要求至少提交一个参数``
字段|描述|是否必须|类型
-|-|-|-|-
nickname|现用密码|N|VARCHAR
addr|新密码|N|VARCHAR
sign|个性签名|N|VARCHAR


**返回结果