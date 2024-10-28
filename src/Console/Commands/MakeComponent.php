<?php

namespace Fantismic\YetAnotherTable\Console\Commands;

use Illuminate\Console\Command;
use function Laravel\Prompts\text;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Livewire\Features\SupportConsoleCommands\Commands\MakeCommand;
use Livewire\Features\SupportConsoleCommands\Commands\ComponentParser;

class MakeComponent extends Command implements PromptsForMissingInput
{

    protected $signature = 'make:yatable {name}';
    protected $description = 'Create a new table';

    protected ComponentParser $parser;

    public function handle()
    {
        $this->parser = new ComponentParser(
            config('livewire.class_namespace'),
            config('livewire.view_path'),
            $this->argument('name')
        );

        $makeCommand = new MakeCommand;

        if ($makeCommand->isReservedClassName($name = $this->parser->className())) {
            $this->line("<fg=red;options=bold>Class is reserved:</> {$name}");
            return;
        }

        $this->createTable();

        $this->info('YATable created: '.$this->parser->className());
    }

    public function createTable() {
        $classPath = $this->parser->classPath();

        if (File::exists($classPath)) {
            $this->line("<fg=red;options=bold>File already exists:</> {$this->parser->relativeClassPath()}");
            return false;
        }

        $this->ensureDirectoryExists($classPath);

        File::put($classPath, $this->generateFileFromStub());
    }

    protected function ensureDirectoryExists($path): void
    {
        if (! File::isDirectory(dirname($path))) {
            File::makeDirectory(dirname($path), 0777, true, true);
        }
    }

    public function generateFileFromStub() {

        $title = ucfirst(strtolower(preg_replace('/(?<!^)([A-Z])/', ' $1', $this->parser->className())));

        if (Schema::hasTable('yat_user_table_config')) {
            $stateHandlerBool = "true";
            $stateHandlerNotice = '';
            $stateHandlerOpenComment = '';
            $stateHandlerCloseComment = '';
        } else {
            $stateHandlerBool = "false";
            $stateHandlerNotice = '// In order to use state handler you have to publish and run the migration!'.PHP_EOL.'        ';
            $stateHandlerOpenComment = '/*';
            $stateHandlerCloseComment = '*/';
        }

        return str_replace(
            ['[namespace]', '[class]','[title]','[stateHandleBool]','[state-handler-notice]','[comment-state-handler-open]','[comment-state-handler-close]'],
            [$this->parser->classNamespace(), $this->parser->className(), $title, $stateHandlerBool,$stateHandlerNotice,$stateHandlerOpenComment,$stateHandlerCloseComment],
            file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'YATableComponent.stub')
        );
    }

    protected function promptForMissingArguments(InputInterface $input, OutputInterface $output): void
    {

        if ($this->didReceiveOptions($input)) {
            return;
        }

        if (trim($this->argument('name')) === '') {
            $name = text('Name of your table?', 'MyTable');

            if ($name) {
                $input->setArgument('name', $name);
            }
        }

    }
}
