<?php

namespace Novascript\IoStreamer\Node;

use Novascript\IoStreamer\Node as BasicNode;

class Set extends BasicNode
{
    protected function custom(): void
    {
        if (isset($this->attrs['source:localfs'])) {
            $path = $this->attrs['source:localfs'];
            $this->wrapper->addLocalFsItems([$path]);
        }
    }
}
