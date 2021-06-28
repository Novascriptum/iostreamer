<?php

namespace Novascript\IoStreamer;

abstract class Node
{
    protected $content = [];
    protected $context = [];

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function execute()
    {
        if (!empty($this->content['@attributes'])) {
            foreach ($this->content['@attributes'] as $attrName => $attrValue) {
                $this->parseAttr($attrName, $attrValue);
            }
        }
        foreach ($this->content as $childNodeName => $childContent) {
            if ('@attributes' == $childNodeName) {
                continue;
            }
            $childNode = NodeFactory::get($childNodeName, $childContent)->execute();
            if (empty($childContent['@attributes']['context-by-ref'])) {
                $childNode->setContext($this->context);
            } else {
                $childNode->setContextByRef($this->context);
            }
            $childNode->execute();
        }
    }

    protected function parseAttr(string $attrName, string $attrValue)
    {
    }

    public function setContext(array $context)
    {
        $this->context = $context;
    }

    public function setContextByRef(array &$context)
    {
        $this->context = &$context;
    }
}
