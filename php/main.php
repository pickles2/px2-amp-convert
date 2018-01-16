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
	 * AMP変換ユーティリティオブジェクトを生成する
	 * @param object $px Picklesオブジェクト
	 * @param object $json プラグインオプション
	 * @return boolean true
	 */
	public static function create_px_amp_convert_utils( $px, $json ){
		$px->amp_convert_utils = new utils($px);
		return true;
	}

	/**
	 * 変換処理の実行
	 * @param object $px Picklesオブジェクト
	 * @param object $json プラグインオプション
	 * @return boolean true
	 */
	public static function exec( $px, $json ){
		$utils = @$px->amp_convert_utils;
		if( !is_object($utils) ){
			$utils = new utils( $px );
		}

		if( !$utils->is_mobile() ){
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
