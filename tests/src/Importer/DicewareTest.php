<?php
namespace AcmeTest\Importer;

use \TestCase;
use \ReflectionClass;
use Acme\Importer\Diceware;

class DicewareTest extends TestCase
{
    public function testImport()
    {
        $dicewareList = 'foo';
        $mock = $this->getMockBuilder(Diceware::class)->disableOriginalConstructor()->onlyMethods([
             'getFromWeb',
             'saveToDisk',
            ])->getMock();
        $mock->expects($this->once())->method('getFromWeb')->will($this->returnValue($dicewareList));
        $mock->expects($this->once())->method('saveToDisk')->with($dicewareList);
        $mock->import();
    }
    
    public function testGetFromWeb()
    {
        $body = 'foo';
        $responseMock = new \GuzzleHttp\Psr7\Response(200, [], $body);
        $clientMock = $this->getMockBuilder(\GuzzleHttp\Client::class)->onlyMethods(['request'])->getMock();
        $clientMock->expects($this->once())->method('request')->with('GET', Diceware::DICEWARE_URL, [])->willReturn($responseMock);
        $mock = $this->getMockBuilder(Diceware::class)->disableOriginalConstructor()->onlyMethods([
            'getHttpClient',
            ])->getMock();
        $mock->expects($this->once())->method('getHttpClient')->will($this->returnValue($clientMock));
        $this->assertSame($body, $this->invoke($mock, 'getFromWeb', []));
    }
    
    public function testSaveToDisk()
    {
        $data = 'foo';
        $fileSystem = $this->createMock(\Illuminate\Contracts\Filesystem\Filesystem::class);
        $fileSystem->expects($this->once())->method('put')->with(Diceware::DICEWARE_FILE, $data);
        
        $fsFactory = $this->createMock(\Illuminate\Contracts\Filesystem\Factory::class);
        $fsFactory->expects($this->once())->method('disk')->with('local')->will($this->returnValue($fileSystem));
        
        $mock = $this->getMockBuilder(Diceware::class)->disableOriginalConstructor()->onlyMethods([
            'getFsFactory',
            ])->getMock();
        $mock->expects($this->once())->method('getFsFactory')->will($this->returnValue($fsFactory));
        $this->invoke($mock, 'saveToDisk', [$data]);
    }
    
    protected function invoke($class, $method, $args)
    {
        $classRef = new ReflectionClass($class);
        $method = $classRef->getMethod($method);
        $method->setAccessible(true);
        return $method->invokeArgs($class, $args);
    }
}