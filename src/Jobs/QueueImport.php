<?php

namespace Nikazooz\Simplesheet\Jobs;

use Nikazooz\Simplesheet\Reader;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Nikazooz\Simplesheet\Facades\Simplesheet;

class QueueImport implements ShouldQueue
{
    use ExtendedQueueable, Dispatchable;

    /**
     * @var string
     */
    public $import;

    /**
     * @var string
     */
    public $filePath;

    /**
     * @var string
     */
    public $readerType;

    public function __construct($import, string $filePath, string $readerType)
    {
        $this->import = $import;
        $this->filePath = $filePath;
        $this->readerType = $readerType;
    }

    /**
     * @param  \Nikazooz\Simplesheet\Reader  $reader
     * @return void
     */
    public function handle(Reader $reader)
    {
        $reader->readNow($this->import, $this->filePath, $this->readerType);
    }
}
