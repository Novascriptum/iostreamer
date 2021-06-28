<?php

namespace Novascript\IoStreamer;

class NodeFactory
{
    public static function get(string $nodeName, array $content)
    {
        $nodeName = \mb_strtolower($nodeName, 'UTF-8');
        $groupName = static::getGroupName($nodeName);
        $className = \implode('\\', \array_filter([__NAMESPACE__, 'Node', $groupName, $nodeName]));
        if (!class_exists($className)) {
            throw new \Exception(\sprintf('Node class "%s" does not exist!', $className));
        }

        return new $className($content);
    }

    protected static function getGroupName(string $nodeName)
    {
        $groups = [
            '_' => 'profile',
        ];

        foreach ($groups as $groupName => $nameToChek) {
            if ($nodeName == $nameToChek) {
                return \str_replace('_', '', $groupName);
            }
        }

        return false;
    }
}
