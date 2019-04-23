# Slim Framework 3 Application
simple CRUD.

## 最低限必要なこと
- テンプレートの変数エスケープ
- CSRF対策
- DBマイグレート
- 認証
- セッション管理


## Installation

```
composer install
composer run install
```

## migrate
```
vendor/bin/phpmig migrate
```

## mysql setting
### mysqlをインストール
```
$ brew update
$ brew install mysql
```

mysql8系でパスワードの暗号化が厳しくなっているので緩くしておく<br>
まずbrewでインストールしたmy.cnfを探す
```
find /usr/local/Cellar/mysql -name "my*.cnf"
/usr/local/Cellar/mysql/8.0.15/.bottle/etc/my.cnf
copy /usr/local/Cellar/mysql/8.0.15/.bottle/etc/my.cnf /etc/my.cnf
sudo vi /etc/my.cnf
```

以下の1行を追記
```
default_authentication_plugin = mysql_native_password #追記
```

mysql起動
```
$ mysql.server start
```

mysqlのセキュリティ設定、適当でおK
```
$ mysql_secure_installation
```

ルートでログイン
```
$ mysql -uroot -p
```

パスワードが８文字以上になっているので６文字以上でLOWに変える
```
mysql> set global validate_password.length=6;
mysql> set global validate_password.policy=LOW;
```

DB・USER作成
```
CREATE DATABASE slimphp;
CREATE USER 'slimphp'@'%' IDENTIFIED WITH mysql_native_password BY 'secret';
GRANT ALL PRIVILEGES ON `slimphp`.* TO 'slimphp'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
```
