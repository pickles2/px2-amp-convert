<?php
/**
 * AMP Converter
 */
namespace tomk79\pickles2\ampConvert;

/**
 * AMP Converter class
 */
class main{

	/**
	 * 変換処理の実行
	 * @param object $px Picklesオブジェクト
	 * @param object $json プラグインオプション
	 */
	public static function exec( $px, $json ){
		if( !preg_match( '/(iPhone|iPod|(Android.*Mobile)|Windows Phone)/', $_SERVER['HTTP_USER_AGENT'] ) ){
			// モバイルのUAでなければ、変換しない。
			return true;
		}

		foreach( $px->bowl()->get_keys() as $key ){
			$src = $px->bowl()->get_clean( $key );

			// AMP 変換
			$amp = new \tomk79\ampConvert\AMPConverter();
			$amp->load($src);
			$src = $amp->convert();

			$px->bowl()->replace( $src, $key );
		}

		return true;
	}
}
