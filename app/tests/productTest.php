<?php

use GuzzleHttp\Client;

class productTest extends PHPUnit_Framework_TestCase {
    protected $client;

    protected function setUp()
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost/webstore/index.php'
        ]);
    }
    
    public function testGet_ProductList() {
        $response = $this->client->request('GET','', [
            'query' => [
                'dispatch' => 'product/list'
            ]
        ]);
        $body = $response->getBody();
        $products = json_decode($body->getContents());
        
        $this->assertObjectHasAttribute('status', $products);
        $this->assertObjectHasAttribute('message', $products);
        $this->assertObjectHasAttribute('data', $products);
        $this->assertEquals(200, $products->status);
        $this->assertEquals('Success', $products->message);
    }
    
    public function testGet_ProductSearch() {
        $response = $this->client->request('GET','', [
            'query' => [
                'dispatch' => 'product/search',
                'name' => 'first',
                'sku' => '1223d'
            ]
        ]);
        $body = $response->getBody();
        $products = json_decode($body->getContents());
        
        $this->assertObjectHasAttribute('status', $products);
        $this->assertObjectHasAttribute('message', $products);
        $this->assertObjectHasAttribute('data', $products);
        $this->assertEquals(200, $products->status);
        $this->assertEquals('Success', $products->message);
    }
    
    public function testGet_ProductView() {
        $response = $this->client->request('GET','', [
            'query' => [
                'dispatch' => 'product/get',
                'product_id' => 3
            ], '_conditional' => ['username' => 'sonu1', 'password' => 123456]
        ]);
        $body = $response->getBody();
        $product = json_decode($body->getContents());
        
        
        $this->assertObjectHasAttribute('status', $product);
        $this->assertObjectHasAttribute('message', $product);
        $this->assertEquals(403, $product->status);
        $this->assertEquals('Unauthorised User', $product->message);
        
        $response = $this->client->request('GET','', [
            'query' => [
                'dispatch' => 'product/get',
                'product_id' => 1
            ], '_conditional' => ['username' => 'sonu', 'password' => 123456]
        ]);
        $body = $response->getBody();
        $product = json_decode($body->getContents());
        
        $this->assertObjectHasAttribute('status', $product);
        $this->assertObjectHasAttribute('message', $product);
        $this->assertObjectHasAttribute('data', $product);
        $this->assertEquals(200, $product->status);
        $this->assertContains($product->message, array('Success', 'invalid product id'));
    }
    
    public function testPost_ProductAdd() {
        $rand = rand(0, 50000);
        $price = rand(0, 1000);
        
        // Test 1 for addition
        $response = $this->client->request('POST','', [
            'query' => [
                'dispatch' => 'product/add'
            ],
            'json' => [
                'name' => 'testProduct-' . $rand,
                'description' => 'Product for testing',
                'sku' => 'sku-testProduct-' . $rand,
                'price' => $price
            ],
            '_conditional' => ['username' => 'sonu1', 'password' => 123456]
        ]);
        $body = $response->getBody();
        $product = json_decode($body->getContents());
                
        $this->assertObjectHasAttribute('status', $product);
        $this->assertObjectHasAttribute('message', $product);
        $this->assertEquals(403, $product->status);
        $this->assertEquals('Unauthorised User', $product->message);
        
        // Test 2
        $response = $this->client->request('POST','', [
            'query' => [
                'dispatch' => 'product/add'
            ],
            'json' => [
                'name' => 'testProduct-' . $rand,
                'description' => 'Product for testing',
                'sku' => 'sku-testProduct-' . $rand,
                'price' => $price
            ],
            '_conditional' => ['username' => 'sonu', 'password' => 123456]
        ]);
        $body = $response->getBody();
        $product = json_decode($body->getContents());
        
        $this->assertObjectHasAttribute('status', $product);
        $this->assertObjectHasAttribute('message', $product);
        $this->assertEquals(200, $product->status);
        $this->assertEquals('product added', $product->message);
        
        
        // Test 3 : sku missing
        $response = $this->client->request('POST','', [
            'query' => [
                'dispatch' => 'product/add'
            ],
            'json' => [
                'name' => 'testProduct-' . $rand,
                'description' => 'Product for testing',
                'price' => $price
            ],
            '_conditional' => ['username' => 'sonu', 'password' => 123456]
        ]);
        $body = $response->getBody();
        $product = json_decode($body->getContents());
        
        $this->assertObjectHasAttribute('status', $product);
        $this->assertObjectHasAttribute('message', $product);
        $this->assertEquals(200, $product->status);
        $this->assertEquals('invalid Product data', $product->message);
    }
    
    public function testPut_ProductUpdate() {
        $rand = rand(0, 50000);
        $price = rand(0, 1000);
        
        // Test 1 for update
        $response = $this->client->request('PUT','', [
            'query' => [
                'dispatch' => 'product/update'
            ],
            'json' => [
                'name' => 'testProduct-' . $rand,
                'description' => 'Product for testing',
                'sku' => 'sku-testProduct-' . $rand,
                'price' => $price,
                'product_id' => 4
            ],
            '_conditional' => ['username' => 'sonu1', 'password' => 123456]
        ]);
        $body = $response->getBody();
        $product = json_decode($body->getContents());
                
        $this->assertObjectHasAttribute('status', $product);
        $this->assertObjectHasAttribute('message', $product);
        $this->assertEquals(403, $product->status);
        $this->assertEquals('Unauthorised User', $product->message);
        
        // Test 2
        $response = $this->client->request('PUT','', [
            'query' => [
                'dispatch' => 'product/update'
            ],
            'json' => [
                'name' => 'testProduct-' . $rand,
                'description' => 'Product for testing',
                'sku' => 'sku-testProduct-' . $rand,
                'price' => $price,
                'product_id' => 4
            ],
            '_conditional' => ['username' => 'sonu', 'password' => 123456]
        ]);
        $body = $response->getBody();
        $product = json_decode($body->getContents());
        
        $this->assertObjectHasAttribute('status', $product);
        $this->assertObjectHasAttribute('message', $product);
        $this->assertEquals(200, $product->status);
        $this->assertContains($product->message, array('product updated', 'invalid product id'));
        
        // Test 3 : sku missing
        $response = $this->client->request('PUT','', [
            'query' => [
                'dispatch' => 'product/update'
            ],
            'json' => [
                'name' => 'testProduct-' . $rand,
                'description' => 'Product for testing',
                'price' => $price,
                'product_id' => 4
            ],
            '_conditional' => ['username' => 'sonu', 'password' => 123456]
        ]);
        $body = $response->getBody();
        $product = json_decode($body->getContents());
        
        $this->assertObjectHasAttribute('status', $product);
        $this->assertObjectHasAttribute('message', $product);
        $this->assertEquals(200, $product->status);
        $this->assertEquals('invalid Product data', $product->message);
        
        // Test 4 : invalid product id
        $response = $this->client->request('PUT','', [
            'query' => [
                'dispatch' => 'product/update'
            ],
            'json' => [
                'name' => 'testProduct-' . $rand,
                'description' => 'Product for testing',
                'price' => $price,
                'sku' => 'sku-testProduct-' . $rand,
                'product_id' => -1
            ],
            '_conditional' => ['username' => 'sonu', 'password' => 123456]
        ]);
        $body = $response->getBody();
        $product = json_decode($body->getContents());
        
        $this->assertObjectHasAttribute('status', $product);
        $this->assertObjectHasAttribute('message', $product);
        $this->assertEquals(200, $product->status);
        $this->assertEquals('invalid product id', $product->message);
    }
    
    public function testDelete_ProductDelete() {
        $response = $this->client->request('DELETE','', [
            'query' => [
                'dispatch' => 'product/delete'
            ],
            'json' => [
                'product_id' => 1
            ],
            '_conditional' => ['username' => 'sonu', 'password' => 123456]
        ]);
        $body = $response->getBody();
        $product = json_decode($body->getContents());
        
        $this->assertObjectHasAttribute('status', $product);
        $this->assertObjectHasAttribute('message', $product);
        $this->assertEquals(200, $product->status);
        $this->assertContains($product->message, array('product deleted', 'invalid product id'));
        
        $response = $this->client->request('DELETE','', [
            'query' => [
                'dispatch' => 'product/delete'
            ],
            'json' => [
                'product_id' => -1
            ],
            '_conditional' => ['username' => 'sonu', 'password' => 123456]
        ]);
        $body = $response->getBody();
        $product = json_decode($body->getContents());
        
        $this->assertObjectHasAttribute('status', $product);
        $this->assertObjectHasAttribute('message', $product);
        $this->assertEquals(200, $product->status);
        $this->assertEquals('invalid product id', $product->message);
    }
}