# pickles2/px2-amp-convert
Pickles 2 コンテンツを AMP に変換します。

## 機能 - Function
`pickles2/px2-amp-convert` は、Pickles 2 コンテンツを AMP に変換します。

変換処理は [tomk79/amp-convert](https://packagist.org/packages/tomk79/amp-convert) に依存します。詳しくは [tomk79/amp-convert](https://packagist.org/packages/tomk79/amp-convert) の README を参照してください。


## 導入手順 - Setup

### 1. composer.json に tomk79/px2-amp-convert を追加

`require` の項目に、`tomk79/px2-amp-convert` を追加します。

```json
{
	"require": {
		"pickles2/px2-amp-convert": "^0.2"
	},
}
```


追加したら、`composer update` を実行して変更を反映することを忘れずに。

```bash
$ composer update
```


### 2. config.php に、プラグインを設定

設定ファイル config.php (通常は `./px-files/config.php`) を編集します。

```php
<?php
	/* 中略 */

	// funcs: Before content
	$conf->funcs->before_content = [
		// AMP変換ユーティリティオブジェクトを生成する
		'tomk79\pickles2\ampConvert\main::create_px_amp_convert_utils()',
	];

	/* 中略 */

	// processor
	$conf->funcs->processor->html = array(

		/* 中略 */

		// AMP変換
		'tomk79\pickles2\ampConvert\main::exec()',

		/* 中略 */

	);
```

Pickles 2 の設定をJSON形式で編集している方は、`config.json` の該当箇所に追加してください。

### 3. モバイルUAでアクセス

モバイルのユーザーエージェントに反応して自動的にAMPに変換されたコンテンツを表示します。


## ユーティリティ - Utilities

```php
<?php

$utils = $px->amp_convert_utils;

if(!is_object($utils)){
	// または
	$utils = new \tomk79\pickles2\ampConvert\utils( $px );
}

// パスの変換パターンを処理する
echo $utils->rewrite_path('/a/b/c/test.html', '{$dirname}/{$filename}.{$ext}'); // '/a/b/c/test.html'

// コールバックを使用する例
echo $utils->rewrite_path('/a/b/c/test.html', function($path){
	return $path.'.test';
}); // '/a/b/c/test.html.test'

```


## 更新履歴 - Change log

### tomk79/px2-amp-convert 0.2.0 (2018年9月25日)

- Pickles 2 グループに移管した。

### tomk79/px2-amp-convert 0.1.1 (2018年3月9日)

- Windowsで起きていた不具合を修正。

### tomk79/px2-amp-convert 0.1.0 (2018年2月4日)

- Initial release.


## ライセンス - License

MIT License


## 作者 - Author

- Tomoya Koyanagi <tomk79@gmail.com>
- website: <http://www.pxt.jp/>
- Twitter: @tomk79 <http://twitter.com/tomk79/>
