# 迷途喵星人

根據統計動物收容所的一半以上動物都是被棄養，流浪動物棄養的問題到現在並未停止過，最近因疫情變得更加嚴重，收容所可容量的動物數量也漸漸減少，本網站的初衷是藉由網站社群呼籲社會大眾以領養代替購買，減少動物收容所的負擔

![App Screenshot](https://i.imgur.com/JP7arx2.png)

## 使用技術

### 前端

- HTML
- CSS
- JavaScript
- Vue.js
- Bootstrap

### 後端

- PHP 8
- Laravel 8

### 資料庫

- MySQL

## 網站功能

### 一般使用者

- 查看喵星人列表
- 瀏覽喵星人資料
- 領養喵星人
- 註冊成為會員

### 網站會員

- 查看喵星人列表
- 瀏覽喵星人資料
- 領養喵星人
- 收藏喵星人
- 刪除收藏喵星人
- 捐款
- 查看捐款紀錄

### 網站管理員

- 新增喵星人資料
- 瀏覽喵星人資料
- 更新喵星人資料
- 刪除喵星人資料
- 新增收容所資料
- 查看收容所資料
- 更新收容所資料
- 刪除收容所資料
- 瀏覽會員捐款紀錄

## 資料庫關聯圖

![App Screenshot](https://i.imgur.com/YDTqlMI.png)

## 安裝與執行 (installation and execution)
1. 選定一個資料夾，用來存放本專案。開啟終端機，移動至該資料夾，下載本專案
```
git clone https://github.com/gg134679852/LostCats.git
```
2. 移動至本專案資料夾
```
cd LostCats
```
3. 安裝套件
```
composer install
```
4. 設定好資料庫環境變數後，執行migrate
```
php artisan migrate
```
5.  執行UserSeeder，產生使用者
```
php artisan db:seed --class=UserSeeder
```
6. 執行command:getCatData，獲取喵星人資料
```
php artisan command:getCatData
```
7. 啟動伺服器
```
php artisan serve
```