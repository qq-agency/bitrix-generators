<?php

namespace QQ\Bitrix\Generators;

use Symfony\Component\Filesystem\Filesystem;

class Structure
{
    protected $source;

    protected $target;

    protected $map;

    protected $search = [];

    protected $replace = [];

    public function __construct($source, $target)
    {
        $this->source = $source;

        $this->target = $target;
    }

    public function replace($search, $replace)
    {
        if (count($search) !== count($replace)) {
            throw new \RuntimeException('Search count doesn\'t match to replace count');
        }

        $this->search = $search;
        $this->replace = $replace;

        return $this;
    }

    public function copy($map)
    {
        $fileSystem = new FileSystem();

        foreach ($map as $destination => $origin) {
            $filesystem = new Filesystem();
            if (is_dir($this->source.$origin)) {
                $fileSystem->mkdir($this->target.$destination);
            } elseif ($filesystem->exists($this->source.$origin)) {
                $filesystem->dumpFile(
                    $this->target.$destination,
                    $this->prepare($this->source.$origin)
                );
            }
        }
    }

    protected function prepare($fileName)
    {
        $content = str_replace(
            $this->search,
            $this->replace,
            file_get_contents($fileName)
        );

        return '<?php'.PHP_EOL.PHP_EOL.$content;
    }
}
