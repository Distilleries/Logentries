<?php
/**
 * Created by PhpStorm.
 * User: cross
 * Date: 2/25/2015
 * Time: 11:07 AM
 */

use \Mockery as m;

class ServiceTest extends \Orchestra\Testbench\TestCase{


    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
    protected function getPackageProviders()
    {
        return array('Distilleries\Logentries\LogentriesServiceProvider');
    }

 
    public function testService()
    {

        $service = $this->app->getProvider('Distilleries\Logentries\LogentriesServiceProvider');
        $facades = $service->provides();
        $this->assertTrue([ 'log' ] == $facades);

        $service->boot();
        $service->register();
    }
} 