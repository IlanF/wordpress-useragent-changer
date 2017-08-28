<?php
/**
 * Class SampleTest
 *
 * @package Firsov_User_Agent_Changer
 */

/**
 * Sample test case.
 */
class Firsov_User_Agent_Changer_Unit_Test extends WP_UnitTestCase {

    /**
	 * Before each test
	 */
	public function setUp() {
        // Make sure we are not making actual HTTP request
        add_filter( 'pre_http_request', '__return_empty_array' );
	}
    
    public function tearDown() {
        delete_option( 'fuac_http_useragent' );
        
        // Remove previous filters
        remove_all_filters( 'http_request_args' );
        remove_filter( 'pre_http_request', '__return_empty_array' );
    }
    
    protected function setUseragent( $useragent ) {
        update_option( 'fuac_http_useragent', $useragent );
    }
    
    protected function assertUseragent( $expected ) {
        remove_all_filters( 'http_request_args' );
        add_filter( 'http_request_args', function( $r, $url=false ) use ( $expected ) {
            $this->assertEquals( $expected, $r['user-agent'] );
            
            return $r;
        }, 2 );
        
        wp_remote_get('http://example.com');
    }

    /**
     * Test that the useragent is set properly when running HTTP requests
     */
	function test_it_sets_the_default_useragent() {
        // save new useragent setting
        $useragent = "my_new_useragent!";
        $this->setUseragent( $useragent );
        
        // check that it changed
        $this->assertUseragent( $useragent );
	}
    
    /**
     * Test that the useragent is restored to the default when the settings is left blank
     */
    function test_it_restores_wordpress_useragent() {        
        global $wp_version;
        
        // save new useragent setting
        $useragent = "my_new_useragent!";
        $this->setUseragent( $useragent );        
        
        // save empty useragent        
        $this->setUseragent( '' );
        
        // check that it returned to default
        $this->assertUseragent( ('WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' )) );
    }
    
    /**
     * Test that other plugins can change the useragent
     */
    function test_it_allows_plugins_to_change_useragent() {
        // save new useragent setting
        $useragent = "my_new_useragent!";
        $this->setUseragent( $useragent );
        
        // add filter to change useragent
        $new_useragent = "modified_useragent";
        add_filter( 'http_headers_useragent', function( $useragent ) use ( $new_useragent ) {
            return $new_useragent;
        } );
        
        // check that the useragent changed to the new instead of ours
        $this->assertUseragent( $new_useragent );
    }
    
}
