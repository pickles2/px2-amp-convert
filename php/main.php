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
			$src = $amp->convert(array(
				'read_file'=>function($path) use ($px){
					if( preg_match('/^data\:([a-zA-Z0-9\_\-]+\/[a-zA-Z0-9\_\-]+)\;base64,(.*)$/i', $path, $matched) ){
						// dataスキーマの場合
						$base64 = $matched[2];
						$content = base64_decode($base64);
						return $content;
					}else if( preg_match('/^[a-zA-Z0-9]+\:/', $path) ){
						// その他のスキーマの場合
						$content = file_get_contents($path);
						return $content;
					}

					// 内部のパスの場合
					if( preg_match('/^\//', $path) ){
						$path_controot = $px->conf()->path_controot;
						$path = preg_replace( '/^\/+/', '/', $path );
						$path = preg_replace( '/^'.preg_quote($path_controot, '/').'/', '', $path );
						$path = preg_replace( '/^\/*/', '/', $path );
					}

					$path = $px->fs()->get_realpath($path, dirname($px->req()->get_request_file_path()));
					$path = $px->fs()->normalize_path($path);
					$content = $px->internal_sub_request($path);
					return $content;
				}
			));

			$px->bowl()->replace( $src, $key );
		}

		return true;
	}
}
