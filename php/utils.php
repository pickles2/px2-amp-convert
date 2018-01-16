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
	 * USER_AGENT がモバイルのものか調べる
	 * @param  string $user_agent 評価する USER_AGENT 文字列。省略時、 `$_SERVER['HTTP_USER_AGENT']` を評価対象とする。
	 * @return boolean モバイルのUAなら `true`、 それ以外の場合には `false`
	 */
	public function is_mobile( $user_agent = null ){
		if( @is_null($user_agent) ){
			$user_agent = @$_SERVER['HTTP_USER_AGENT'];
		}
		if( !preg_match( '/(iPhone|iPod|(Android.*Mobile)|Windows Phone)/', $user_agent ) ){
			// モバイルのUAでなければ、false を返す
			return false;
		}
		return true;
	}

	/**
	 * amphtml のURLを取得する
	 */
	public function amphtml($pattern, $path = null){
		if( is_null($path) ){
			$path = $this->px->site()->get_current_page_info()['path'];
		}
		$rtn = $this->rewrite_path($path, $pattern);
		$rtn = $this->px->canonical($rtn);
		return $rtn;
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
			if($data['dirname'] == dirname($data['dirname'])){
				// ルート階層にある場合、スラッシュが余計に増えてしまうので、消す。
				$data['dirname'] = '';
			}
			$path_rewrited = str_replace( '{$dirname}', $data['dirname'], $path_rewrited );
			$path_rewrited = str_replace( '{$filename}', $data['filename'], $path_rewrited );
			$path_rewrited = str_replace( '{$ext}', $data['ext'], $path_rewrited );
			return $path_rewrited;
		}
		return $path;
	}

}
