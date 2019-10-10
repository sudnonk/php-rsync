# php-rsync
phpでrsyncを便利に使えるようにするクラスです。

## prerequisite 前提
- ふつうにrsyncコマンドが使えること

## install 
`composer require sudnonk/php-rsync`

## environment 環境
- PHP 7.1.10以上
- Bash

## usage 使い方
**注意**

実際に実行する前に、一度発行されるコマンドを確認してください。ファイルが消えても責任は取りません。
```php
$rsync->run();//の前に！！！！！！！！！
echo $rsync->build(); //で確認すること！！！！！！
```

`sample.php`も参照

### コンストラクタ
```php
$rsync = new Rsync();
```

標準出力に`rsync`コマンドの出力を出したいときは、
```php
$rsync = new Rsync(true);
```

### コピー元のディレクトリをセット
```php
$rsync->set_from(string ディレクトリ名,bool 中身だけか, string ユーザ名, string ホスト);

//例
$rsync->set_from("/remote/test",true,"root","localhost");
$rsync->set_from("/local/test2",false);
```

第二引数を`true`にすると、ディレクトリの中身だけをコピーします。
`false`にすると、ディレクトリ自体をコピーします。
ファイルをコピーしたいときは`false`にしてください。

コピー元がリモートの時は、第三と第四引数を指定してください。ローカルの時はなにも入れなくて良いです。

### コピー先のディレクトリをセット
```php
$rsync->set_to(string ディレクトリ名, string ユーザ名, string ホスト);

//例
$rsync->set_to("/remote/test","root","localhost");
$rsync->set_to("/local/test2");
```

コピー先は、自動で指定したディレクトリの中にコピーする扱いになります。

コピー先がリモートの時は、第三と第四引数を指定してください。ローカルの時はなにも入れなくて良いです。

### オプションをセット
```php
//パラメータつきのオプションをセットするとき
$rsync->options()->set(string オプション名, string パラメータ);
//パラメータ無しのをまとめてセットするとき
$rsync->options()->sets(string オプション名, string オプション名, ...);

//例
$rsync->options()->set("ignore-times");
$rsync->options()->set("timeout", "5");
$rsync->options()->sets("a", "v", "z", "stats");
```

オプションを指定できます。どんなオプションがあるかはググってください。たぶん大体対応してます。

良く使うオプションについては、関数を用意してあります。

```php
//コピー元にないファイルをコピー先から削除する
$rsync->options()->setDelete();
//どんな動作をするかを実際には行わずに確認する
$rsync->options()->setDryRun();
```

#### SSHのオプションをセット
```php
//SSHのオプションをセット
$rsync->ssh_options()->set(string オプション名, string パラメータ);

//例
$rsync->ssh_options()->set("C");
$rsync->ssh_options()->set("F", "/path/to/config");
```

SSHのオプションを指定できます。どんなオプションがあるかはググってください。たぶん大体対応してます。
                          
良く使うオプションについては、関数を用意してあります。

```php
//接続先ポートを10022番にする
$rsync->ssh_options()->setPort(10022);
//公開鍵ファイルを指定する
$rsync->ssh_options()->setCert("/path/to/cert");
```

### rsyncを実行する
```php
//実行する
$rsync->run();

//コマンドだけを組み立てる
$command = $rsync->build();
var_dump($command);
//rsync -a -u -z --stat -e 'ssh -i /path/to/cert' /local/test root@example.com:/remote/test
```

とくにオプションはありません。

