<?php

namespace Nikazooz\Simplesheet\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Nikazooz\Simplesheet\Files\TemporaryFile;
use Nikazooz\Simplesheet\Writer;

class AppendDataToSheet implements ShouldQueue
{
    use Queueable, Dispatchable, ProxyFailures;

    /**
     * @var array
     */
    public $data = [];

    /**
     * @var string
     */
    public $temporaryFile;

    /**
     * @var string
     */
    public $writerType;

    /**
     * @var int
     */
    public $sheetIndex;

    /**
     * @var object
     */
    public $sheetExport;

    /**
     * @param  object  $sheetExport
     * @param  TemporaryFile  $temporaryFile
     * @param  string  $writerType
     * @param  int  $sheetIndex
     * @param  array  $data
     */
    public function __construct($sheetExport, TemporaryFile $temporaryFile, string $writerType, int $sheetIndex, array $data)
    {
        $this->sheetExport = $sheetExport;
        $this->data = $data;
        $this->temporaryFile = $temporaryFile;
        $this->writerType = $writerType;
        $this->sheetIndex = $sheetIndex;
    }

    /**
     * Get the middleware the job should be dispatched through.
     *
     * @return array
     */
    public function middleware()
    {
        return (method_exists($this->sheetExport, 'middleware')) ? $this->sheetExport->middleware() : [];
    }

    /**
     * @param Writer $writer
     * @throws \Box\Spout\Common\Exception\IOException
     */
    public function handle(Writer $writer)
    {
        $writer = $writer->reopen($this->temporaryFile, $this->writerType);

        $sheet = $writer->getSheetByIndex($this->sheetIndex);

        $rowsToAppend = $sheet->getRowsToAppend($this->data, $this->sheetExport);

        $writer->reopen($this->temporaryFile, $this->writerType, $rowsToAppend, $this->sheetIndex);
    }
}
