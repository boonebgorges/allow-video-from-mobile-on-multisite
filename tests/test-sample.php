<?php

class AVFMOM_Tests extends WP_XMLRPC_UnitTestCase {
	protected $old_current_user = 0;
	protected $author_id = 0;
	protected $admin_id = 0;

	public function setUp() {
		parent::setUp();

		$this->old_current_user = get_current_user_id();

		$upload_dir = wp_upload_dir();
		$this->video_url = $upload_dir['url'] . '/201302222-095719.mov';
	}

	public function tearDown() {
		parent::tearDown();
		wp_set_current_user( $this->old_current_user );
	}

	public function test_raw_video_tag_strip_nonxmlrpc() {
		$user_id = $this->factory->user->create( array( 'role' => 'author', ) );
		$content = '<br /><br /><video src="' . $this->video_url . '" controls="controls" width="480" height="360">Your browser does not support the video tag</video>';
		$post_id = $this->factory->post->create( array( 'post_content' => $content, ) );
		$post = get_post( $post_id );
		$this->assertNotEquals( $content, $post->post_content );
	}

	public function test_raw_video_tag_admin() {
		$this->make_user_by_role( 'administrator' );
		$content = '<br /><br /><video src="' . $this->video_url . '" controls="controls" width="480" height="360">Your browser does not support the video tag</video>';
		$post_args = array( 'post_title' => 'Test', 'post_content' => $content, );
		$result = $this->myxmlrpcserver->wp_newPost( array( 1, 'administrator', 'administrator', $post_args ) );
		$post = get_post( $result );
		$this->assertEquals( $content, $post->post_content );
	}

	public function test_raw_video_tag_author() {
		$this->make_user_by_role( 'author' );
		$content = '<br /><br /><video src="' . $this->video_url . '" controls="controls" width="480" height="360">Your browser does not support the video tag</video>';
		$post_args = array( 'post_title' => 'Test', 'post_content' => $content, );
		$result = $this->myxmlrpcserver->wp_newPost( array( 1, 'author', 'author', $post_args ) );
		$post = get_post( $result );
		$this->assertEquals( $content, $post->post_content );
	}

	public function test_object_admin() {
		wp_set_current_user( $this->admin_id );
		$content = 'Test 11<br /><br /><object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" width="224" height="144"><param name="src" value="' . $this->video_url . '"><param name="autoplay" value="false"><embed src="' . $this->video_url . '" autoplay="false" width="224" height="144" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/" /></object>';
		$post_id = $this->factory->post->create( array( 'post_content' => $content ) );
		$post = get_post( $post_id );
		$this->assertEquals( $content, $post->post_content );
	}

	public function test_object_author() {
		wp_set_current_user( $this->author_id );
		$content = 'Test 11<br /><br /><object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" width="224" height="144"><param name="src" value="' . $this->video_url . '"><param name="autoplay" value="false"><embed src="' . $this->video_url . '" autoplay="false" width="224" height="144" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/" /></object>';
		$post_id = $this->factory->post->create( array( 'post_content' => $content ) );
		$post = get_post( $post_id );
		$this->assertEquals( $content, $post->post_content );
	}
}

