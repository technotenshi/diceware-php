<?php

use Acme\Importer\Diceware;
use App\Console\Commands\ImportData;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Console\Tester\CommandTester;

class CommandTest extends TestCase
{
    public function testHttpFailureStopsImport()
    {
        $factory = $this->createMock(Illuminate\Contracts\Filesystem\Factory::class);
        $factory->expects($this->never())->method('disk');
        $client = new Client(['handler' => HandlerStack::create(new MockHandler([new Response(503)]))]);
        $importer = new Diceware($factory, $client);
        $command = new ImportData($importer);
        $command->setLaravel($this->app);
        $tester = new CommandTester($command);
        try {
            $tester->execute([]);
            $this->fail('HTTP errors must propagate');
        } catch (GuzzleHttp\Exception\ServerException $exception) {
            $this->assertStringContainsString('Getting diceware data', $tester->getDisplay());
            $this->assertStringNotContainsString('Retrieved data', $tester->getDisplay());
        }
    }

    public function testFilesystemFailureStopsImport()
    {
        $disk = $this->createMock(Illuminate\Contracts\Filesystem\Filesystem::class);
        $disk->method('put')->willThrowException(new RuntimeException('Storage unavailable'));
        $factory = $this->createMock(Illuminate\Contracts\Filesystem\Factory::class);
        $factory->method('disk')->with('local')->willReturn($disk);
        $client = new Client(['handler' => HandlerStack::create(new MockHandler([new Response(200, [], 'fixture')]))]);
        $command = new ImportData(new Diceware($factory, $client));
        $command->setLaravel($this->app);
        $tester = new CommandTester($command);
        try {
            $tester->execute([]);
            $this->fail('Filesystem errors must propagate');
        } catch (RuntimeException $exception) {
            $this->assertSame('Storage unavailable', $exception->getMessage());
            $this->assertStringNotContainsString('Retrieved data', $tester->getDisplay());
        }
    }

    public function testQueueBaseClassLoads()
    {
        $this->assertTrue(is_subclass_of(App\Jobs\Job::class, Illuminate\Contracts\Queue\ShouldQueue::class));
    }
}
