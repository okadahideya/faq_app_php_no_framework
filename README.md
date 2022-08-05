# 目次
 - 概要
 - 機能
 - 使用技術
 - 追加課題

## 概要
FAQアプリ
 - 管理者側で利用者アカウント作成
 - 一問一答
 - カテゴリ検索

## 機能
 - ユーザー(管理者)
  - 利用者作成
  - 利用者編集
  - 利用者削除
  - ユーザー一覧
 - ログイン
 - FAQ
  - 質問作成
  - 質問編集
  - 質問削除
  - 回答作成
  - 回答編集
  - 回答削除
  - 質問一覧表示

## 使用技術
 - ユーザー
  - passwordは、暗号化などで保存
 - ログイン
  - セッション認証
  - cookie



# ローカル環境(Docker)
## 起動
```bash
$ docker-compose up

### ビルド有り
$ docker-compose up --build
```

## 停止

```bash
$ docker-compose stop
```

## コンテナサービスにログイン
```bash
$ docker exec -it php_php_1 bash
$ docker exec -it php_db_1 bash
```

## Dockerをビルド
```bash
Dockerイメージビルド
$ docker-compose build retty_web_backend_nginx

Dockerコンテナの実行
$ docker-compose up retty_web_backend_nginx
```


## ブラウザーでアクセス
http://localhost:80  


## ローカルDBの接続情報
```
  - ホスト名
    - 127.0.0.1
  - ユーザ名
    - root
  - パスワード
    - root
  - DB名
    - study
  - ポート
    - 3306
```
## docekr mysql ログイン
```bash
docker コンテナ名確認
$ docker ps

docker ログイン
$ docker exec -it [コンテナ名] mysql -u root -p

mysql ログアウト
mysql> $ exit
```

## mysql 確認コマンド
```bash
データベース確認
mysql> $ show databases;

データベースの選択
mysql> $ use データベース名;

テーブルが持つ列や型といったテーブルの構造を確認する
mysql> $ DESC テーブル名;

カラムの追加
myaql> $ alter table テーブル名 add(カラム名 型, name TEXT);
```

## mysql テーブル、カラム削除
```bash
カラム削除
mysql> alter table テーブル名 drop column カラム名;

テーブル削除
mysql> drop table テーブル名;
```

## mysql データ挿入
```bash
一括でデータ挿入 
**文字列の場合は、””を囲むこと**
mysql> $ insert into テーブル名 (カラム名,id,name,,,) value (1,"orange",200),(2,"peach",300),(3,"apple",100);

insert into users (id,name,email,password,role,create_at) value (1,"tanaka","tanaka@gmail.com","root",1,20220509);
```

## myaql テーブル名users
```bash
テーブル作成クエリ
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint NOT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

```