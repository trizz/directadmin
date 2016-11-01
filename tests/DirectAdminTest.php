<?php
use PHPUnit\Framework\TestCase;

class DirectAdminTest extends TestCase
{
    public function testConstructorSettings()
    {
        $class = $this->getMockForAbstractClass(
            \Trizz\DirectAdmin\DirectAdmin::class,
            [
                'http://test.host',
                'user',
                'pass',
                'domain.com',
                1234
            ]
        );

        $this->assertAttributeEquals('http://test.host:1234', 'baseUrl', $class);
        $this->assertAttributeEquals('user', 'username', $class);
        $this->assertAttributeEquals('pass', 'password', $class);
        $this->assertAttributeEquals('domain.com', 'domain', $class);
        $this->assertAttributeEquals('user', 'username', $class);
    }
}