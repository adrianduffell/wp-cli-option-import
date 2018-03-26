# WP-CLI Option Import

A WP-CLI package providing a command to import WordPress options from a .yml file.

## Requirements

- WordPress
- WP-CLI

## Installation

Download this branch (dev) as a .zip file from GitHub and install the package using WP-CLI.
Install on the command line via Composer.
```shell
wp package install /path/to/.zip
```

## Usage

Create a .yml file containing option values to be imported.

```yml
blogname: Testing
blogdescription: Not another WordPress site.
```

Import the file.
```sh
$ wp option import /path/to/.options.yml
Success: Updated 2 of 4 options (2 skipped).
```
