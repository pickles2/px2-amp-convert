<?php
/**
 * test
 */

class mainTest extends PHPUnit_Framework_TestCase{

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
	 * Px2を実行してみる
	 */
	public function testStandard(){

		// トップページを実行
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/.px_execute.php' , '/'] );
		// var_dump($output);
		$this->assertSame( preg_match('/'.preg_quote('<img src="index_files/photo008.jpg" alt="Image 1" />', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<img src="./index_files/photo008.jpg" alt="Image 2" />', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<img src="/index_files/photo008.jpg" alt="Image 3" />', '/').'/s', $output), 1 );

		// トップページを実行 (mobile USER_AGENT)
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/.px_execute.php', '-u', 'iPhone' , '/'] );
		// var_dump($output);

		$this->assertTrue( gettype($output) == gettype('') );
		$this->assertSame( preg_match('/'.preg_quote('<amp-img src="index_files/photo008.jpg" alt="Image 1" width="800" height="600" layout="responsive"></amp-img>', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<amp-img src="./index_files/photo008.jpg" alt="Image 2" width="800" height="600" layout="responsive"></amp-img>', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<amp-img src="/index_files/photo008.jpg" alt="Image 3" width="800" height="600" layout="responsive"></amp-img>', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('.style-test {', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('.style2{', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('.base64{color:#0f0;}', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('.cont-index-test-css', '/').'/s', $output), 1 );

	}//testStandard()


	/**
	 * サブディレクトリにセットアップされたPx2を実行してみる
	 */
	public function testSubdir(){

		// トップページを実行
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/subdir/.px_execute.php' , '/'] );
		// var_dump($output);
		$this->assertSame( preg_match('/'.preg_quote('<img src="index_files/photo008sub.jpg" alt="Image 1" />', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<img src="./index_files/photo008sub.jpg" alt="Image 2" />', '/').'/s', $output), 1 );

		// トップページを実行 (mobile USER_AGENT)
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/subdir/.px_execute.php', '-u', 'iPhone' , '/'] );
		// var_dump($output);

		$this->assertTrue( gettype($output) == gettype('') );
		$this->assertSame( preg_match('/'.preg_quote('<amp-img src="https://github.com/pickles2/px2-amp-convert/blob/master/tests/testdata/standard/index_files/photo008.jpg?raw=true" alt="Remote Image 1" width="800" height="600" layout="responsive"></amp-img>', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<amp-img src="//github.com/pickles2/px2-amp-convert/blob/master/tests/testdata/standard/index_files/photo008.jpg?raw=true" alt="Remote Image 2" width="800" height="600" layout="responsive"></amp-img>', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<amp-img src="index_files/photo008sub.jpg" alt="Image 1" width="800" height="600" layout="responsive"></amp-img>', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<amp-img src="./index_files/photo008sub.jpg" alt="Image 2" width="800" height="600" layout="responsive"></amp-img>', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<amp-img src="/subdir/index_files/photo008sub.jpg" alt="Image 3" width="800" height="600" layout="responsive"></amp-img>', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('.subdir-style-test {', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('.subdir-style2{', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('.base64{color:#0f0;}', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('.cont-index-test-css', '/').'/s', $output), 1 );

	}//testSubdir()


	/**
	 * サブディレクトリにセットアップされたPx2の少し深いコンテンツを実行してみる
	 */
	public function testSubdirDeep(){

		// トップページを実行
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/subdir/.px_execute.php' , '/hoge/fuga/foo.html'] );
		// var_dump($output);
		$this->assertSame( preg_match('/'.preg_quote('<img src="foo_files/foo.jpg" alt="Image 1-1" />', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<img src="./foo_files/foo.jpg" alt="Image 1-2" />', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<img src="/subdir/hoge/fuga/foo_files/foo.jpg" alt="Image 1-3" />', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<img src="../../index_files/photo008sub.jpg" alt="Image 2-1" />', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<img src="../.././index_files/photo008sub.jpg" alt="Image 2-2" />', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<img src="/subdir/index_files/photo008sub.jpg" alt="Image 2-3" />', '/').'/s', $output), 1 );

		// トップページを実行 (mobile USER_AGENT)
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/subdir/.px_execute.php', '-u', 'iPhone' , '/hoge/fuga/foo.html'] );
		// var_dump($output);

		$this->assertTrue( gettype($output) == gettype('') );
		$this->assertSame( preg_match('/'.preg_quote('<amp-img src="foo_files/foo.jpg" alt="Image 1-1" width="800" height="600" layout="responsive"></amp-img>', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<amp-img src="./foo_files/foo.jpg" alt="Image 1-2" width="800" height="600" layout="responsive"></amp-img>', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<amp-img src="/subdir/hoge/fuga/foo_files/foo.jpg" alt="Image 1-3" width="800" height="600" layout="responsive"></amp-img>', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<amp-img src="../../index_files/photo008sub.jpg" alt="Image 2-1" width="800" height="600" layout="responsive"></amp-img>', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<amp-img src="../.././index_files/photo008sub.jpg" alt="Image 2-2" width="800" height="600" layout="responsive"></amp-img>', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('<amp-img src="/subdir/index_files/photo008sub.jpg" alt="Image 2-3" width="800" height="600" layout="responsive"></amp-img>', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('.subdir-style-test {', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('.subdir-style2{', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('.base64{color:#0f0;}', '/').'/s', $output), 1 );
		$this->assertSame( preg_match('/'.preg_quote('.cont-index-test-css', '/').'/s', $output), 1 );

	}//testSubdirDeep()


	/**
	 * 後始末
	 */
	public function testFinal(){

		// 後始末
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/subdir/.px_execute.php' , '/?PX=clearcache'] );
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/.px_execute.php' , '/?PX=clearcache'] );

	}//testFinal()




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
