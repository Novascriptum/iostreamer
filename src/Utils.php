<?php

namespace Novascript\IoStreamer;

class Utils
{
    /**
     * @param array $optDesc options description: [
     *                       'p' => 'profile:*',
     *                       't' => ['test', false],
     *                       ]
     */
    public static function getOptions(array $optDesc): array
    {
        $shortToFull = []; // ['f' => 'full-param-name']
        $multiOpts = [];
        $boolOpts = [];
        $shortSpecification = '';
        $fullSpecification = [];
        $defaults = [];
        foreach ($optDesc as $shortName => $info) {
            if (\is_array($info)) {
                $fullDescriptor = $info[0];
                $default = $info[1];
            } else {
                $fullDescriptor = $info;
                $default = null;
            }
            $isMulti = (bool) \preg_match('/\*$/', $fullDescriptor);
            $fullDescriptor = \rtrim($fullDescriptor, '*');
            $reqPostfix = \preg_replace('/^.*?(:*)$/', '$1', $fullDescriptor);
            $fullName = \rtrim($fullDescriptor, ':');
            if (!$reqPostfix) {
                $boolOpts[$fullName] = true;
            }
            if (!\is_null($default)) {
                $defaults[$fullName] = $default;
            }
            if ($isMulti) {
                $multiOpts[$fullName] = true;
            }
            $shortToFull[$shortName] = $fullName;
            $shortSpecification .= $shortName.$reqPostfix;
            $fullSpecification[] = $fullName.$reqPostfix;
        }
        // $rawOpts = getopt($shortSpecification, $fullSpecification) + $defaults;
        $rawOpts = getopt($shortSpecification, $fullSpecification);
        $rawOpts = \array_intersect_key($boolOpts, $rawOpts) + $rawOpts + $defaults;

        $result = [];
        foreach ($rawOpts as $optCode => $mixedValue) {
            $optCode = $shortToFull[$optCode] ?? $optCode;
            if (isset($result[$optCode])) {
                $result[$optCode] = \array_merge((array) $result[$optCode], (array) $mixedValue);
            } else {
                $result[$optCode] = $mixedValue;
            }
        }

        foreach ($result as $fullName => &$mixedValue) {
            if (isset($multiOpts[$fullName])) {
                $mixedValue = (array) $mixedValue;
            } elseif (\is_array($mixedValue)) {
                throw new \Exception('Shell option "'.$fullName.'" should has only one value! Values '.implode(',', $mixedValue).' given.');
            }
        }

        return $result;
    }
}
