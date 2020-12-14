# ローカル(MAMP)環境で作成したPHP(Twig)をAWSのWEBサーバにアップロードし、公開したい。

## 使用技術・バージョン(自分のローカル環境・MAMPに合わせた)
+ PHP...7.4.*(7.4ならおけ)
+ MySQL... 5.7.*
+ Twig
+ AWS
  + 主にEC2のWEBサーバにログインして作業(ターミナル操作必須)
+ SCP(Linuxコマンド)
+ 

## 前提
+ UdemyのAWS講座(山浦さん)をセクション６まで終えている。
  + VPC,EC2,Route53,RDSの設定を終えており、WEBサーバのドメインを取得している。
  + RDSに入れたMysqlのバージョンがローカルの環境に準拠している。(Udemy講座の時点でバージョン指定する)
  + EC2のWEBサーバにSSH接続が出来るようになっている

## 手順(``の部分はコマンドか設定ファイル編集)

### サーバの言語を日本語対応化
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo sed -i "s/en_US\.UTF-8/ja_JP\.UTF-8/g" /etc/sysconfig/i18n `

### PHPのインストール

+ PHP7.4のインストール
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo amazon-linux-extras install epel `
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo yum install epel-release  `
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm `
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo yum install -y php74 php74-php php74-php-fpm `
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo amazon-linux-extras install epel `
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo ln -s /usr/bin/php74 /usr/bin/php `
  ` [ec2-user@ip-10-0-10-10 ~]$ php -v `
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo find / -name php.ini `
  ` [ec2-user@ip-10-0-10-10 ~]$ ln -s /etc/opt/remi/php74/php.ini /etc/php.ini `
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo ln -s /etc/opt/remi/php74/php.ini /etc/php.ini `

###  httpd・php-fpmの起動 

+ httpd & PHPの起動 & EC2立ち上げ時に自動起動するように設定

  ` [ec2-user@ip-10-0-10-10 ~]$ sudo systemctl start httpd.service `
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo systemctl status httpd.service `
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo systemctl enable httpd.service `
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo systemctl is-enabled httpd.service `
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo systemctl start php74-php-fpm.service `
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo systemctl status php74-php-fpm.service `
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo systemctl enable php74-php-fpm.service `
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo systemctl is-enabled php74-php-fpm.service `




### Composerのインストール
  ` [ec2-user@ip-10-0-10-10 ~]$ php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" `
  ` [ec2-user@ip-10-0-10-10 ~]$ php composer-setup.php `
  ` [ec2-user@ip-10-0-10-10 ~]$ rm composer-setup.php `
  ` [ec2-user@ip-10-0-10-10 ~]$ mv composer.phar /usr/local/bin/composer`
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo mv composer.phar /usr/local/bin/composer `
  ` [ec2-user@ip-10-0-10-10 ~]$ composer `

### Document Rootの変更
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo vi /etc/httpd/conf/httpd.conf `
+ /でDocumentRoot(アクセス先)を任意のフォルダに指定(INSERTモードでない時(なってたらesc押す)に  :set number→エンター)
  ` 118 Documentroot "/var/www/html" ` 


### ローカルからポートフォリオのフォルダ(templatesフォルダも含む)をアップロード(今回はscpコマンドを使用)
+ ポートフォリオはMAMP/XAMPPからデスクトップに丸ごとコピー
  + ` cp -r /Applications/XAMPP/xamppfiles/htdocs/DT/ECweb ~/desktop `
+ ターミナルを開く
+ ` cd ~/desktop/ECweb `
+ 複製したポートフォリオフォルダ内の作業
  + ls -la
※rmの-fはきょうせいしょうきょ
  + rm -rf .git (gitのフォルダ。重いのでここでは消す)
  + rm -rf logs/* (ログも重いので中身を全消去)

ssh -i ~/.ssh/my_private_key root@ec2-12-34-56-78.ap-northeast-1.compute.amazonaws.com

scp -i $HOME/.ssh/AWS_TOKYO_portfolio.pem /Users/my_name/Desktop/up_dir/up_file.html ec2-user@{pablic_IP_address}.compute.amazonaws.com:

  ` scp -i AWS_TOKYO_portfolio.pem /desktop/ECweb ec2-user@3.114.89.166.compute.amazonaws.com `
+ scp -i 'EC2の秘密鍵' -r 'サーバに送りたいフォルダ名' ec2-user@ホスト名(IP):'Documentrootのフルパス(自分の場合は"/var/www/html)'  ※コマンド入力時の'は不要。

### EC2にてTwigをインストール→3ファイルをDocumentRoot配下に移動

+ ` [ec2-user@ip-10-0-10-10 ~]$ cd /usr/local/bin `
+ ` [ec2-user@ip-10-0-10-10 bin]$ sudo php composer require twig/twig:1.* `
+ lsでcomposer.json、 composer.lock、 vendor(フォルダ)の3つがある事を確認
  + ` [ec2-user@ip-10-0-10-10 ~]$ ls `
+ 3ファイルをDocumentRoot配下に
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo mv composer.json composer.lock vendor /var/www/html/ `
+ 移動して、あるか確認。
  ` [ec2-user@ip-10-0-10-10 ~]$ cd /var/www/html/`
  ` [ec2-user@ip-10-0-10-10 ~]$ ls `

### EC2にてPDOをインストール→設定ファイル変更
+ mysqlnd.so  pdo_mysql.o pdo.so  があるか確認
  ` [ec2-user@ip-10-0-10-10 ~]$ cd /opt/remi/php74/root/usr/lib64/php/modules/`
+ EC2にはPDOがデフォルトで無いので各種インストール
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo yum install --enablerepo=remi,remi-php74 php74-php-mysqlnd php74-php-pdo `


+ php.iniの修正
  ` [ec2-user@ip-10-0-10-10 ~]$ sudo vi /etc/php.ini`
  + 最下行に以下を追加

extension=/opt/remi/php74/root/usr/lib64/php/modules/pdo.so
extension=/opt/remi/php74/root/usr/lib64/php/modules/mysqlnd.so
extension=/opt/remi/php74/root/usr/lib64/php/modules/pdo_mysql.so


### MySQLの設定 → 
CREATE DATABASE ECweb_db DEFAULT CHARACTER SET utf8;

+ GRANT ALL PRIVILEGESの部分(ユーザーの作成、権限付与)を以下に変更。
ex) grant all privileges on ECweb_db.*to root@'%' identified by '******' with grant option;
  ` > GRANT ALL PRIVILEGES ON `%`.* TO '任意のユーザ名'@'%' IDENTIFIED BY '任意のパスワード' WITH GRANT OPTION; `
+ ユーザ確認する
  ` > select host,user,select_priv,create_priv,insert_priv,grant_priv,account_locked from mysql.user; `
  + おそらく追加したユーザの行が全部Nになっている(権限が無い)

+ RDSではGRANT ALLで権限が与えられないので、rootユーザーと同じ権限を与えてあげる
  + 以下でrootの権限情報が出るのでコピー
    ` > show grants for 'root'@`%`; `
  + 作ったユーザに権限を与える
    ` > GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, RELOAD, PROCESS, REFERENCES, INDEX, ALTER, SHOW DATABASES, CREATE TEMPORARY TABLES, LOCK TABLES, EXECUTE, REPLICATION SLAVE, REPLICATION CLIENT, CREATE VIEW, SHOW VIEW, CREATE ROUTINE, ALTER ROUTINE, CREATE USER, EVENT, TRIGGER ON *.* TO 'root'@`%` WITH GRANT OPTION; `
  + 権限を確認(Yになっているか)
    ` > select host,user,select_priv,create_priv,insert_priv,grant_priv,account_locked from mysql.user; `


+ データベース→テーブルの順に作成し、データをinsert(xammpからエクスポートできるかも)

### 実際に表示して終了！！
+ http://nagatomo-portfolio.work/shopping/item_list.php
+ http://nagatomo-portfolio.work/shopping/item_list
+ http://nagatomo-portfolio/item_list.php.work
  http://nagatomo-portfolio.work/privacy_policy.html