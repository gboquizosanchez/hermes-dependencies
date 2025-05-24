<?php

declare(strict_types=1);

namespace Hermes\Utilities;

use JetBrains\PhpStorm\NoReturn;

class Help
{
    #[NoReturn]
    public static function execute(): void
    {
        $helpText =
        <<<'EOT'
        +++   +++       ++  +++          +     +         ++     +++          
        +++   +++     +++   +++++        ++   +++      +++    ++++           
        +++   +++   +++     +++++++     ++++  +++    +++    ++++             
         +++++++  +++++++   ++  ++++    ++++ ++++  +++++++   ++++           
         +++++++  +++++++   ++++++     ++++++++++  +++++++    +++++         
        +++   +++    +++    +++++++    ++  +++ +++    +++        ++++        
        +++   +++      +++  +++  +++  +++  ++   +++     +++    ++++          
        +++   +++       ++  +++   +++ ++    +   +++      ++   +++            

        Usage: vendor/bin/hermes [options]

        Options:
            -c, --composer       Generate Composer dependencies
            -p, --package        Generate NPM dependencies
            -a, --all            Generate all dependencies
            --output=<file>      Specify the output file (default: DEPENDENCIES)
            -h, --help           Show this help message

        EOT;
        fwrite(STDOUT, $helpText);
        exit(0);
    }
}
