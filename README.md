# DoDo

可以多人協作 to-do-list , 在這裡你可以與朋友一起建立目標，代辦事項。或是在這個圖像化的卡片中記錄生活中的美好。

## 專案簡介

DoDo 為簡易的任務管理 App，我與前端 IOS 合作作品。此 App 俱備登入系統，使用者有多張 Card，一張 Card 中可以新增多個 Task，俱備多人操作 Card 和 task 的 CRUD 功能，俱備上傳使用者頭像功能。

使用者可以自由創立卡片，並再卡片上新增任務，然後邀請別的使用者共同編輯卡片。

## 使用技術

-   框架：Laravel
-   資料庫：mysql
    設計關聯式資料庫，透過關聯，讓使用者登入後只能看到自己以及群組的 card，以及使用 CRUD 時只能操作自己的 card。使用 Laravel 的 migration 功能，可以快速在雲端佈署資料庫。

-   雲端主機：GCP Compute Engine
-   web server：Apache
-   OS：Ubuntu18.04
-   檔案儲存：GCP Storage
    使用者頭像儲存在 google storage，將其公開網址存在資料庫

-   Log：使用 Laravel 的 middleware，擷取 request 和 response 內容
-   驗證格式：使用 Laravel 的 Validation 功能，驗證使用者輸入參數。
-   版本控制：Git

## 圖片

![](https://i.imgur.com/LNrZmBH.png)
![](https://i.imgur.com/Wbx3Abr.png)
![](https://i.imgur.com/GqMyuv5.png)
![](https://i.imgur.com/qmqd96B.png)
![](https://i.imgur.com/gEhcPS0.png)
![](https://i.imgur.com/7CpVpIL.png)
