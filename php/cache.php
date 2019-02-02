<?php
/**
 * AMP Converter
 */
namespace tomk79\pickles2\ampConvert;

/**
 * AMP Converter Resource Cache
 */
class cache{

	/** Picklesオブジェクト */
	private $px;

	/** キャッシュディレクトリ */
	private $realpath_amp_cache;

	/**
	 * 変換処理の実行
	 * @param object $px Picklesオブジェクト
	 */
	public function __construct( $px ){
		$this->px = $px;
		$realpath_cache = $px->get_realpath_homedir();
		$this->realpath_amp_cache = $realpath_cache.'_sys/ram/caches/px2-amp-convert/';
		if( !is_dir( $this->realpath_amp_cache ) ){
			$this->px->fs()->mkdir_r( $this->realpath_amp_cache );
		}
	}

	/**
	 * パスをキャッシュのインデックス文字列に変換する
	 * @param string $path 対象のパス
	 * @return string インデックス
	 */
	private function path2index( $path ){
		$index = urlencode($path);
		return $index;
	}

	/**
	 * キャッシュが存在するか確認する
	 * @param string $path 調べる対象のパス
	 * @return boolean 存在すれば `true` 、 存在しなければ `false` を返します。
	 */
	public function is_cache( $path ){
		$index = $this->path2index($path);
		if( !is_file( $this->realpath_amp_cache.$index ) ){
			// キャッシュファイルが存在しない
			return false;
		}

		$expire_limit = 30*60;//秒
		$mtime = filemtime( $this->realpath_amp_cache.$index );
		if( $mtime + $expire_limit < time() ){
			// 有効期限切れ
			return false;
		}
		return true;
	}

	/**
	 * キャッシュから取得
	 * @param string $path 取得する対象のパス
	 * @return string キャッシュされたファイルの内容を返します。キャッシュが存在しない場合 `false` を返します。
	 */
	public function get_cache_content( $path ){
		$index = $this->path2index($path);
		if( !is_file( $this->realpath_amp_cache.$index ) ){
			return false;
		}
		$content = file_get_contents( $this->realpath_amp_cache.$index );
		return $content;
	}

	/**
	 * 内容をキャッシュする
	 * @param string $path キャッシュする対象のパス
	 * @param string $content ファイルの内容
	 * @return boolean 成功すれば `true` 、 失敗すれば `false` を返します。
	 */
	public function save_cache_content( $path, $content ){
		$index = $this->path2index($path);
		$result = $this->px->fs()->save_file( $this->realpath_amp_cache.$index, $content );
		return $result;
	}
}
