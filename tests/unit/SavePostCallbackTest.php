<?php

namespace TDS;

use TDS\Tools\TestCase;
use WP_Mock;

class SavePostCallbackTest extends TestCase {

	public function setUp() {
		parent::setUp();
		balancing_relationship( false );
	}

	public function tearDown() {
		balancing_relationship( false );
		parent::tearDown();
	}

	public function test_callback_noop_when_balancing_tags() {
		balancing_relationship( true );
		WP_Mock::userFunction( 'wp_set_object_terms', array( 'times' => 0 ) );
		$post = (object) array( 'ID' => rand( 1, 9 ) );
		call_user_func( get_save_post_hook( 'foo', 'bar' ), $post->ID, $post );
		$this->assertConditionsMet();
	}

}
