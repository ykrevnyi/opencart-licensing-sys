<?php

class KeyRepositoryTest extends TestCase {


	/**
	 * Test find key by `key` proporty
	 *
	 * @return void
	 */
	public function testFindKey()
	{
		$repo = new \License\Repositories\KeyRepository();
		$key = $repo->find('DEMO');

		print_r($key);
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