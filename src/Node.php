<?php

namespace Novascript\IoStreamer;

use Novascript\IoStreamer\Node\Wrapper;

abstract class Node
{
    protected $attrs = [];
    protected $wrapper;
    protected $content = [];
    protected $context = [];

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function execute()
    {
        $this->initialize();
        if (!$this->validate()) {
            return false;
        }
        $this->custom();
        $this->processChildren();
    }

    protected function initialize(): void
    {
        if (!empty($this->content['@attributes'])) {
            foreach ($this->content['@attributes'] as $attrName => $attrValue) {
                $this->parseAttr($attrName, $attrValue);
            }
        }
    }

    protected function validate(): bool
    {
        return true;
    }

    protected function custom(): void
    {
    }

    protected function processChildren(): void
    {
        foreach ($this->content as $childNodeName => $childContent) {
            if ('@attributes' == $childNodeName) {
                continue;
            }
            $childNode = NodeFactory::get($childNodeName, $childContent);
            if (empty($childContent['@attributes']['context-by-ref'])) {
                $childNode->setContext($this->context);
            } else {
                $childNode->setContextByRef($this->context);
            }
            $this->beforeChildExecute($childNode);
            $childNode->execute();
        }
    }

    protected function beforeChildExecute(Node $child): void
    {
    }

    protected function parseAttr(string $attrName, string $attrValue): void
    {
        $attrName = \mb_strtolower($attrName, 'UTF-8');
        if ($attrValue = \trim($attrValue)) {
            $this->attrs[$attrName] = $attrValue;
        }
    }

    public function setContext(array $context)
    {
        $this->context = $context;
    }

    public function setContextByRef(array &$context)
    {
        $this->context = &$context;
    }

    public function setWrapper(Wrapper $wrapper): void
    {
        $this->wrapper = $wrapper;
    }
}
