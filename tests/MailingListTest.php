<?php
use PHPUnit\Framework\TestCase;

class MailingListTest extends TestCase
{
    public function testLists()
    {
        $class = $this->getMockBuilder(\Trizz\DirectAdmin\MailingList::class)
            ->setConstructorArgs([null, null, null, null])
            ->setMethods(['sendRequest'])
            ->getMock();

        $class->expects($this->any())
            ->method('sendRequest')
            ->with('/CMD_API_EMAIL_LIST')
            ->willReturn([
                'testList' => '1:2',
                'testList2' => '3:4',
            ]);

        $this->assertEquals(
            [
                [
                    'name' => 'testList',
                    'subscribers' => '1',
                    'digest_subscribers' => '2'
                ],
                [
                    'name' => 'testList2',
                    'subscribers' => '3',
                    'digest_subscribers' => '4'
                ]
            ],
            $class->lists()
        );
    }

    public function testAddList()
    {
        $class = $this->getMockBuilder(\Trizz\DirectAdmin\MailingList::class)
            ->setConstructorArgs([null, null, null, null])
            ->setMethods(['sendRequest'])
            ->getMock();

        $class->expects($this->any())
            ->method('sendRequest')
            ->with(
                '/CMD_API_EMAIL_LIST',
                [
                    'action' => 'create',
                    'name' => 'testList',
                ]
            )
            ->willReturn(true);

        $this->assertTrue($class->addList('testList'));
    }

    public function testDeleteList()
    {
        $class = $this->getMockBuilder(\Trizz\DirectAdmin\MailingList::class)
            ->setConstructorArgs([null, null, null, null])
            ->setMethods(['sendRequest'])
            ->getMock();

        $class->expects($this->any())
            ->method('sendRequest')
            ->with(
                '/CMD_API_EMAIL_LIST',
                [
                    'action' => 'delete',
                    'select0' => 'testList',
                ]
            )
            ->willReturn(true);

        $this->assertTrue($class->deleteList('testList'));
    }

    public function testGetSubscribers()
    {
        $class = $this->getMockBuilder(\Trizz\DirectAdmin\MailingList::class)
            ->setConstructorArgs([null, null, null, null])
            ->setMethods(['sendRequest'])
            ->getMock();

        $class->expects($this->any())
            ->method('sendRequest')
            ->with(
                '/CMD_API_EMAIL_LIST',
                [
                    'action' => 'view',
                    'name' => 'testList',
                ]
            )
            ->willReturn([
                's01' => 'test@example.com',
                'd01' => 'test@example.org',
            ]);

        $this->assertEquals(
            [
                'subscribers' => ['test@example.com'],
                'digest_subscribers' => ['test@example.org'],
            ],
            $class->getSubscribers('testList')
        );

        $this->assertEquals(['test@example.com'], $class->getSubscribers('testList', 'subscribers'));
        $this->assertEquals(['test@example.org'], $class->getSubscribers('testList', 'digest_subscribers'));
    }

    public function testAddAddress()
    {
        $class = $this->getMockBuilder(\Trizz\DirectAdmin\MailingList::class)
            ->setConstructorArgs([null, null, null, null])
            ->setMethods(['sendRequest'])
            ->getMock();

        $class->expects($this->any())
            ->method('sendRequest')
            ->with(
                '/CMD_API_EMAIL_LIST',
                [
                    'action' => 'add',
                    'name' => 'testList',
                    'type' => 'list',
                    'email' => 'test@example.com',
                ]
            )
            ->willReturn(true);

        $this->assertTrue($class->addAddress('test@example.com', 'testList'));
    }

    public function testAddAddresses()
    {
        $class = $this->getMockBuilder(\Trizz\DirectAdmin\MailingList::class)
            ->setConstructorArgs([null, null, null, null])
            ->setMethods(['sendRequest'])
            ->getMock();

        $class->expects($this->any())
            ->method('sendRequest')
            ->with(
                '/CMD_API_EMAIL_LIST',
                [
                    'action' => 'add',
                    'name' => 'testList',
                    'type' => 'digest',
                    'email' => 'test@example.com,test@example.org',
                ]
            )
            ->willReturn(true);

        $this->assertTrue($class->addAddresses(['test@example.com', 'test@example.org'], 'testList', 'digest'));
    }

    public function testDeleteAddress()
    {
        $class = $this->getMockBuilder(\Trizz\DirectAdmin\MailingList::class)
            ->setConstructorArgs([null, null, null, null])
            ->setMethods(['sendRequest'])
            ->getMock();

        $class->expects($this->any())
            ->method('sendRequest')
            ->with(
                '/CMD_API_EMAIL_LIST',
                [
                    'action' => 'delete_subscriber',
                    'name' => 'testList',
                    'select0' => 'test@example.com',
                ]
            )
            ->willReturn(true);

        $this->assertTrue($class->deleteAddress('test@example.com', 'testList'));
    }

    public function testDeleteAddresses()
    {
        $class = $this->getMockBuilder(\Trizz\DirectAdmin\MailingList::class)
            ->setConstructorArgs([null, null, null, null])
            ->setMethods(['sendRequest'])
            ->getMock();

        $class->expects($this->any())
            ->method('sendRequest')
            ->with(
                '/CMD_API_EMAIL_LIST',
                [
                    'action' => 'delete_subscriber_digest',
                    'name' => 'testList',
                    'select0' => 'test@example.com',
                    'select1' => 'test@example.org',
                ]
            )
            ->willReturn(true);

        $this->assertTrue($class->deleteAddresses(['test@example.com', 'test@example.org'], 'testList', 'digest'));
    }
}