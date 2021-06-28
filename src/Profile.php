<?php

namespace Novascript\IoStreamer;

class Profile
{
    protected $name;
    protected $profileDir;

    public function __construct(string $profileName)
    {
        $this->profileDir = \implode(\DIRECTORY_SEPARATOR, [
            \dirname(__DIR__), 'config', 'profiles',
        ]);
        $this->name = $profileName;
    }

    public function execute(): bool
    {
        $profilePath = \sprintf('%s%s%s.xml', $this->profileDir, \DIRECTORY_SEPARATOR, $this->name);
        if (!$this->checkFile($profilePath)) {
            return false;
        }
        $rootXmlNode = \simplexml_load_file($profilePath);
        NodeFactory::get($rootXmlNode->getName(), $this->xmlToArray($rootXmlNode))->execute();

        return true;
    }

    public function checkFile(string $filepath): bool
    {
        if (!\file_exists($filepath)) {
            Report::show(\sprintf("Profile \"%s\" not found and will be ignored\n", $filepath));

            return false;
        }
        if (\is_dir($filepath)) {
            Report::show(\sprintf("Profile \"%s\" should not be a directory\n", $filepath));

            return false;
        }

        return true;
    }

    protected function xmlToArray($xml)
    {
        if (empty($xml) || \is_scalar($xml)) {
            return $xml;
        }
        $xml = (array) $xml;
        foreach ($xml as &$child) {
            $child = $this->{__FUNCTION__}($child);
        }

        return \array_filter($xml);
    }
}
