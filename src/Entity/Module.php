<?php

namespace QQ\Bitrix\Generators\Entity;

use QQ\Bitrix\Generators\Structure;

class Module
{
    protected $defaultName;

    protected $upperCaseName;

    protected $snackCaseName;

    protected $lang = [];

    public function __construct($moduleName, $lang)
    {
        $this->defaultName = $moduleName;
        $this->upperCaseName = mb_strtoupper(str_replace('.', '_', $moduleName));
        $this->snackCaseName = mb_strtolower(str_replace('.', '_', $moduleName));

        $this->lang = $lang;
    }

    public function compile($target)
    {
        $sourceDirectory = __DIR__.'/../Stubs/module';

        $map = [
            '/install/index.php' => '/install/index.stub',
            '/install/version.php' => '/install/version.stub',
            '/.settings.php' => '/.settings.stub',
            '/default_option.php' => '/default_option.stub',
            '/include.php' => '/include.stub',
            '/options.php' => '/options.stub',
            '/prolog.php' => '/prolog.stub',
            '/lib/' => '/lib/'
        ];

        foreach ($this->lang as $lang) {
            $map['/lang/'.$lang.'/install/index.php'] = '/lang/common/install/index.stub';
        }

        $structure = new Structure($sourceDirectory, $target);
        $structure
            ->replace(
                [
                    '{{ $defaultName }}',
                    '{{ $upperCaseName }}',
                    '{{ $snackCaseName }}',
                    '{{ $currentDate }}',
                ],
                [
                    $this->getDefaultName(),
                    $this->getUpperCaseName(),
                    $this->getSnackCaseName(),
                    date('Y-m-d H:i:s')
                ]
            )
            ->copy($map);
    }

    public function getDefaultName()
    {
        return $this->defaultName;
    }

    public function getUpperCaseName()
    {
        return $this->upperCaseName;
    }

    public function getSnackCaseName()
    {
        return $this->snackCaseName;
    }
}
