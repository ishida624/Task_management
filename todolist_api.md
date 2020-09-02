
# todolsit api


 編號 |   method    | Uri |    header  | body    |說明
 --|--| -----|-----|------|------
1|   GET    |   api/todolist    |      userToken |            | 列出todolist
2|   POST    | api/todolist   |     userToken | item    | 新增todos
3|   GET/HEAD | api/todolist/{todolist} | userToken |        | 查詢todos
4|   PUT/PATCH |    api/todolist/{todolist} |  userToken | item       |更新
5|   DELETE |   api/todolist/{todolist} |   userToken |        | 刪除

====================================================================================
# response(json)

## 1.列出todolist

status code | 解釋
----|---
200 |OK

### 回應範例：

項目 | 說明
---|----
no | 編號
item | 內容
stutes  |   完成狀態
update_time | 更新時間
update_user |   更新者

<br>

##  2.新增todos

status code | 解釋
----|---
201 |OK
400 | 無效的請求

<br>

##  3.查詢todos

status code | 解釋
----|---
200 |OK
400 | 找不到資源


### 回應範例：

項目 | 說明
---|----
no | 編號
item | 內容
stutes  |   完成狀態
update_time | 更新時間
update_user |   更新者

<br>

##  4.更新

status code | 解釋
----|---
201 | 更新成功
400 | 無效的請求

<br>

##  5.刪除

status code | 解釋
----|---
201 | 刪除成功
400 | 無效的請求
