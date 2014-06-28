<?php

class ExampleTest extends TestCase {


	/**
	 * Test find key by `key` proporty
	 *
	 * @return void
	 */
	public function testFindKey()
	{
		// $repo = new KeyRepository();
		// $key = $repo->find('DEMO');

		// assertEquals($key->id, actual, 'message', delta, maxDepth, canonicalize, ignoreCase);
	}


	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testBasicExample()
	{
		$crawler = $this->client->request('GET', '/');

		$this->assertTrue($this->client->getResponse()->isOk());
	}


}