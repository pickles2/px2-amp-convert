<?php
/**
 * test
 */

class utilsTest extends PHPUnit_Framework_TestCase{

	/**
	 * ファイルシステムユーティリティ
	 */
	private $fs;

	/**
	 * setup
	 */
	public function setup(){
		$this->fs = new \tomk79\filesystem();
	}

	/**
	 * パス書き換えのテスト
	 */
	public function testRewritePath(){

		$cd = realpath('.');
		$SCRIPT_FILENAME = $_SERVER['SCRIPT_FILENAME'];
		chdir(__DIR__.'/testdata/standard/');
		$_SERVER['SCRIPT_FILENAME'] = __DIR__.'/testdata/standard/.px_execute.php';

		$px = new picklesFramework2\px('./px-files/');
		$toppage_info = $px->site()->get_page_info('');
		// var_dump($toppage_info);
		$this->assertEquals( $toppage_info['title'], 'HOME' );
		$this->assertEquals( $toppage_info['path'], '/index.html' );
		$this->assertEquals( $_SERVER['HTTP_USER_AGENT'], '' );

		$utils = new \tomk79\pickles2\ampConvert\utils( $px );
		$this->assertEquals( $utils->rewrite_path('/a/b/c/test.html', '{$dirname}/{$filename}.{$ext}'), '/a/b/c/test.html' );
		$this->assertEquals( $utils->rewrite_path('/a/b/c/test.html', function($path){
			return $path.'.test';
		}), '/a/b/c/test.html.test' );
		$this->assertEquals( $utils->rewrite_path('/a/b/c/test.html', 'function($path){return $path.\'.test2\';}'), '/a/b/c/test.html.test2' );
		$this->assertEquals( $utils->rewrite_path('/a/b/c/test.html', null), '/a/b/c/test.html' );


		// サブリクエストでキャッシュを消去
		$output = $px->internal_sub_request(
			'/index.html?PX=clearcache',
			array(),
			$vars
		);
		$error = $px->get_errors();
		// var_dump($output);
		// var_dump($vars);
		// var_dump($error);
		$this->assertTrue( is_string($output) );
		$this->assertSame( 0, $vars ); // <- strict equals
		$this->assertSame( array(), $error );


		chdir($cd);
		$_SERVER['SCRIPT_FILENAME'] = $SCRIPT_FILENAME;

		$px->__destruct();// <- required on Windows
		unset($px);

	} // testRewritePath()



	/**
	 * コマンドを実行し、標準出力値を返す
	 * @param array $ary_command コマンドのパラメータを要素として持つ配列
	 * @return string コマンドの標準出力値
	 */
	private function passthru( $ary_command ){
		set_time_limit(180); // Windowsのtestがタイム・アウトするため追加
		$cmd = array();
		foreach( $ary_command as $row ){
			$param = '"'.addslashes($row).'"';
			array_push( $cmd, $param );
		}
		$cmd = implode( ' ', $cmd );
		ob_start();
		passthru( $cmd );
		$bin = ob_get_clean();
		return $bin;
	}// passthru()

}
