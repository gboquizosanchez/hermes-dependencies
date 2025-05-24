# Hermes
This tool allows extracting all Composer and NPM dependencies in a cool format.

## Install 🔧
`composer require --dev hermes/dependencies`

## How it works ⚙️
Hermes works typing in CLI: 

`vendor/bin/hermes`

The following arguments can be added:

`-c` or `--composer`: Extract Composer dependencies into a markdown file.

`-p` or `--package`: Extract NPM dependencies into a markdown file.

`-a` or `--all`: Extract NPM dependencies and composer dependencies into a markdown file.

`-h` or `--help`: Show help message.

`--path`: Path can be changed if your project has submodules.

`--output`: Output is configurable to be extract where it wants.

## Authors ✒️
This project was made by:

* **Germán Boquizo Sánchez** - *Fullstack developer*
* **Pablo Álvarez Martín** - *Fullstack developer - Original idea*
