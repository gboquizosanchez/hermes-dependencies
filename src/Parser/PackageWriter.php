<?php

declare(strict_types=1);

namespace Hermes\Parser;

use Midnite81\JsonParser\JsonParse;

class PackageWriter extends Writer
{
    /**
     * @var object
     */
    private $jsonFile;

    public function __construct(string $path, string $output)
    {
        parent::__construct($path, $output);

        try {
            $this->jsonFile = $this->decodedFile($path);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function parse(): void
    {
        if (isset($this->jsonFile)) {
            $values = ['normal' => '### NPM dependencies 📦', 'dev' => '#### Develop dependencies 🔧'];

            foreach ($values as $key => $value) {
                $type = $key === 'normal' ? '' : $key;

                $this->writeDependencies($this->dependencies($type), $value, 'npm');
            }
        }
    }

    private function decodedFile(string $path)
    {
        $filename = "{$path}/package.json";

        if (! file_exists($filename)) {
            throw new \RuntimeException("Not found {$filename}");
        }

        $file = file_get_contents($filename);

        return JsonParse::decode($file);
    }

    private function dependencies(string $type): array
    {
        $attribute = $type === 'dev' ? 'devDependencies' : 'dependencies';

        return (array) $this->jsonFile->$attribute;
    }
}
