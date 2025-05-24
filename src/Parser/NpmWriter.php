<?php

declare(strict_types=1);

namespace Hermes\Parser;

use Exception;
use Hermes\Exceptions\NotFoundFilename;
use Hermes\Utilities\JsonParse;
use Hermes\Utilities\PackageType;

class NpmWriter extends Writer
{
    private object $jsonFile;

    public function init(): void
    {
        parent::init();

        try {
            $this->jsonFile = $this->decodedFile($this->path);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function parse(): void
    {
        if (isset($this->jsonFile)) {
            $values = [
                'normal' => '### NPM dependencies 📦',
                'dev' => '#### Develop dependencies 🔧'
            ];

            foreach ($values as $key => $value) {
                $type = $key === 'normal' ? '' : $key;

                $this->writeDependencies(
                    $this->dependencies($type),
                    $value,
                    PackageType::Npm->value,
                );
            }
        }
    }

    private function decodedFile(string $path)
    {
        $filename = "{$path}/package.json";

        if (! file_exists($filename)) {
            throw new NotFoundFilename("Not found {$filename}");
        }

        $file = file_get_contents($filename);

        return JsonParse::decode($file);
    }

    private function dependencies(string $type): array
    {
        $attribute = $type === 'dev' ? 'devDependencies' : 'dependencies';

        if (! property_exists($this->jsonFile, $attribute)) {
            return [];
        }

        return (array) $this->jsonFile->$attribute;
    }
}
