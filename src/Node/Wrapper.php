<?php

namespace Novascript\IoStreamer\Node;

use Novascript\IoStreamer\Node as BasicNode;

class Wrapper extends BasicNode
{
    protected $outputStream;
    /**
     * tar | zip etc.
     */
    public function setArchiveEngine(string $engineName)
    {
    }

    /**
     * Если $outputPath пустой, то wrapper должен записывать переданный ему поток напрямую в свой
     * output-stream без посредничества (в который он сам пишет данные - получится
     * склейка архивов).
     */
    public function setStreamToPullFrom(resource $streamId, ?string $outputPath = null)
    {
        // Тут запускается код по чтению $streamId и его записи в нужный output
        // (будь то собственный output текущего процесса или же дочерний подпроцесс)
    }

    public function addLocalFsItems(array $items, int $offset = 0)
    {
        foreach($items as $path){
            $content = \file_get_contents($path);
            \fwrite($this->outputStream, $content);
        }
    }

    protected function initOutputStream()
    {
        $this->outputStream = \STDOUT;
    }

    protected function beforeChildExecute(BasicNode $child): void
    {
        $child->setWrapper($this);
    }

    protected function custom(): void
    {
        $this->initOutputStream();
    }
}
