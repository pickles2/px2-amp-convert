<?php
/**
 * AMP Converter
 */
namespace tomk79\pickles2\ampConvert;

/**
 * AMP Converter Utility
 */
class utils{

	/** Picklesオブジェクト */
	private $px;

	/**
	 * 変換処理の実行
	 * @param object $px Picklesオブジェクト
	 */
	public function __construct( $px ){
		$this->px = $px;
	}

	/**
	 * パスを変換する
	 * @param  string $path     変換前のパス
	 * @param  mixed  $pattern コールバック関数 または 変換ルール文字列
	 * @return [type]           変換後のパス
	 */
	public function rewrite_path($path, $pattern){
		if( is_null($pattern) ){
			// コールバック関数が設定されなかった場合
			return $path;
		}elseif( is_callable($pattern) ){
			// コールバック関数が設定された場合
			return call_user_func( $pattern, $path );
		}elseif( is_string($pattern) && strpos(trim($pattern), 'function') === 0 ){
			// function で始まる文字列
			return call_user_func(eval('return '.$pattern.';'), $this->px->fs()->normalize_path($path) );
		}elseif( is_string($pattern) ){
			// パターンを表す文字列
			$path_rewrited = $pattern;
			$data = array(
				'dirname'=>$this->px->fs()->normalize_path(dirname($path)),
				'filename'=>basename($this->px->fs()->trim_extension($path)),
				'ext'=>strtolower($this->px->fs()->get_extension($path)),
			);
			$path_rewrited = str_replace( '{$dirname}', $data['dirname'], $path_rewrited );
			$path_rewrited = str_replace( '{$filename}', $data['filename'], $path_rewrited );
			$path_rewrited = str_replace( '{$ext}', $data['ext'], $path_rewrited );
			return $path_rewrited;
		}
		return $path;
	}

}
