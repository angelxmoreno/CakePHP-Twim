<?php

App::import('Datasource', 'Twim.TwimSource');

ConnectionManager::create('test_twitter_twim_source', array(
    'datasource' => 'Twim.TwimSource',
    'oauth_consumer_key' => 'cvEPr1xe1dxqZZd1UaifFA',
    'oauth_consumer_secret' => 'gOBMTs7Rw4Z3p5EhzqBey8ousRTwNDvreJskN8Z60',
));

/**
 *
 * @property TwimSource $TwimSource
 * @property stdClass $Model
 */
class TwimSourceTestCase extends CakeTestCase {

    public function startTest($method) {
        $this->TwimSource = ConnectionManager::getDataSource('test_twitter_twim_source');
        $this->Model = new stdClass();
    }

    public function endTest($method) {
        unset($this->Model);
        unset($this->TwimSource);
        ClassRegistry::flush();
    }

    // =========================================================================
    public function testRequest() {
        $this->Model->request = array(
            'uri' => array(
                'host' => 'search.twitter.com',
                'path' => 'search',
                'query' => array('q' => 'twitter'),
            ),
        );
        $results = $this->TwimSource->request($this->Model);
        $this->assertIdentical(200, $this->TwimSource->Http->response['status']['code']);
        $this->assertTrue(isset($results['results']));
    }

    // =========================================================================
    public function testConfigProxy() {
        $this->Model->request = array(
            'uri' => array(
                'host' => 'search.twitter.com',
                'path' => 'search',
                'query' => array('q' => 'twitter'),
            ),
        );
        $this->TwimSource->configProxy('localhost');
        $results = $this->TwimSource->request($this->Model);
        $this->assertIdentical(200, $this->TwimSource->Http->response['status']['code']);
        $this->assertTrue(isset($results['results']));
    }

}