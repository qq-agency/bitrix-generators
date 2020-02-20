<?php

namespace QQ\Bitrix\Generators\Entity;

use QQ\Bitrix\Generators\Structure;

class Template
{
    protected $defaultName;

    public function __construct($templateName)
    {
        $this->defaultName = $templateName;
    }

    public function compile($target)
    {
        $sourceDirectory = __DIR__.'/../Stubs/template';

        $map = [
            '/.styles.php' => '/.styles.stub',
            '/description.php' => '/description.stub',
            '/footer.php' => '/footer.stub',
            '/header.php' => '/header.stub',
            '/styles.css' => '/styles.css',
            '/template_styles.css' => '/template_styles.css',
        ];

        $structure = new Structure($sourceDirectory, $target);
        $structure
            ->replace(
                [
                    '{{ $defaultName }}',
                ],
                [
                    $this->getDefaultName(),
                ]
            )
            ->copy($map);
    }

    public function getDefaultName()
    {
        return $this->defaultName;
    }
}
