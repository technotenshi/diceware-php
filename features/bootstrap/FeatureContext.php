<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;

class FeatureContext implements Context, SnippetAcceptingContext
{
    private $output;
    private $storage;

    /** @BeforeScenario */
    public function prepareStorage()
    {
        $this->storage = sys_get_temp_dir().'/diceware-behat-'.bin2hex(random_bytes(8));
        mkdir($this->storage.'/app', 0700, true);
        mkdir($this->storage.'/logs', 0700, true);
        touch($this->storage.'/app/database.sqlite');
        $this->runCommand('migrate --force --no-ansi');
    }

    /** @AfterScenario */
    public function removeStorage()
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->storage, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
        }
        rmdir($this->storage);
    }

    /** @Given I am in the root directory */
    public function iAmInTheRootDirectory()
    {
        chdir(dirname(__DIR__, 2));
    }

    /** @When I run :command */
    public function iRun($command)
    {
        if (!str_starts_with($command, 'php artisan ')) {
            throw new InvalidArgumentException('Expected an Artisan command');
        }
        $this->runCommand(substr($command, strlen('php artisan ')));
    }

    private function runCommand($command)
    {
        $process = proc_open([
            PHP_BINARY, __DIR__.'/../support/console.php', $this->storage, $command,
        ], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, dirname(__DIR__, 2));
        $this->output = trim(stream_get_contents($pipes[1]));
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        if (proc_close($process) !== 0) {
            throw new RuntimeException($this->output."\n".$error);
        }
    }

    /** @Then I should get: */
    public function iShouldGet(PyStringNode $string)
    {
        if ((string) $string !== $this->output) {
            throw new RuntimeException("Actual output is:\n".$this->output);
        }
    }

    /** @Then the diceware migration should be marked as run */
    public function migrationShouldBeRun()
    {
        if (!preg_match('/2015_04_21_195843_create_diceware_table[^\n]*Ran/', $this->output)) {
            throw new RuntimeException("Unexpected migration status:\n".$this->output);
        }
    }

    /** @Then file should exists :path */
    public function fileShouldExists($path)
    {
        $path = $this->storage.'/'.substr($path, strlen('storage/'));
        if (!is_file($path) || file_get_contents($path) !== "11111\tabacus\n") {
            throw new RuntimeException('Imported fixture missing or incorrect: '.$path);
        }
    }
}
