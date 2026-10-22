<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Acme\Importer\Diceware as DicewareImporter;

class ImportData extends Command 
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'util:importData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports diceware data';
  
    /**
     * Diceware importer class
     * 
     * @var DicewareImporter
     */
    protected $importer;
  
    /**
     * Sets the diceware importer
     * 
     * @param DicewareImporter $importer
     */
    public function setDicewareImporter(DicewareImporter $importer)
    {
        $this->importer = $importer;
    }
  
    /**
     * Gets the diceware importer
     * 
     * @return DicewareImporter
     */
    public function getDicewareImporter()
    {
        return $this->importer;
    }

    /**
     * Create a new command instance.
     * 
     * @param DicewareImporter $importer
     *
     */
    public function __construct(DicewareImporter $importer)
    {
    $this->setDicewareImporter($importer);
    parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    $this->info('Getting diceware data');
    $this->getDicewareImporter()->import();
    $this->info('Retrieved data');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
    return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
    return array();
    }
}