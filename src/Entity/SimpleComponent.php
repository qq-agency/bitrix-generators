<?php

namespace QQ\Bitrix\Generators\Entity;

use QQ\Bitrix\Generators\Structure;

class SimpleComponent
{
    protected $fullName;

    protected $vendorCode;

    protected $defaultName;

    protected $camelCaseName;

    protected $upperCaseName;

    protected $lang = [];

    public function __construct($componentName, $lang)
    {
        if (strpos($componentName, '.') === false) {
            throw new \RuntimeException('Component name must contain dot separator');
        }

        $this->fullName = $componentName;

        $componentName = explode('.', $componentName);

        $this->vendorCode = $componentName[0];
        $this->defaultName = $componentName[1];

        $this->camelCaseName = ucfirst($this->vendorCode).ucfirst($this->defaultName);
        $this->upperCaseName = mb_strtoupper($this->vendorCode.'_'.$this->defaultName);

        $this->lang = $lang;
    }

    public function getFullName()
    {
        return $this->fullName;
    }

    public function compile($target)
    {
        $sourceDirectory = __DIR__.'/../Stubs/simple-component';

        $map = [
            '/templates/.default/template.php' => '/templates/.default/template.stub',
            '/.description.php' => '/.description.stub',
            '/.parameters.php' => '/.parameters.stub',
            '/class.php' => '/class.stub',
        ];

        foreach ($this->lang as $lang) {
            $map['/lang/'.$lang.'/templates/.default/template.php'] = '/lang/common/templates/.default/template.stub';
            $map['/lang/'.$lang.'/.description.php'] = '/lang/common/.description.stub';
            $map['/lang/'.$lang.'/.parameters.php'] = '/lang/common/.parameters.stub';
        }

        $structure = new Structure($sourceDirectory, $target);
        $structure
            ->replace(
                [
                    '{{ $vendorCode }}',
                    '{{ $defaultName }}',
                    '{{ $camelCaseName }}',
                    '{{ $upperCaseName }}',
                ],
                [
                    $this->getVendorCode(),
                    $this->getDefaultName(),
                    $this->getCamelCaseName(),
                    $this->getUpperCaseName(),
                ]
            )
            ->copy($map);
    }

    public function getVendorCode()
    {
        return $this->vendorCode;
    }

    public function getDefaultName()
    {
        return $this->defaultName;
    }

    public function getCamelCaseName()
    {
        return $this->camelCaseName;
    }

    public function getUpperCaseName()
    {
        return $this->upperCaseName;
    }
}
