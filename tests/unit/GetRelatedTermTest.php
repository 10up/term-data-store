<?php

namespace TDS;

use TDS\Tools\TestCase;
use WP_Mock;

class GetRelatedTermTest extends TestCase {

	public function test_get_related_term_noop_when_no_post() {
		WP_Mock::wpFunction( 'get_post', array( 'return' => null ) );
		$this->assertNull( get_related_term( 1 ) );
	}

}
