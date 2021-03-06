# Itonomy - Backup module

[![Latest Stable Version](https://poser.pugx.org/itonomy/module-backup/v/stable)](https://packagist.org/packages/itonomy/module-backup)
[![Latest Unstable Version](https://poser.pugx.org/itonomy/module-backup/v/unstable)](https://packagist.org/packages/itonomy/module-backup)
[![Total Downloads](https://poser.pugx.org/itonomy/module-backup/downloads)](https://packagist.org/packages/itonomy/module-backup)
[![Latest Version](https://img.shields.io/packagist/v/itonomy/module-backup.svg)](https://github.com/itonomy/module-backup/releases)
[![Build Status](https://travis-ci.org/Itonomy/module-backup.svg?branch=master)](https://travis-ci.org/Itonomy/module-backup)
[![Coverage Status](https://coveralls.io/repos/github/Itonomy/module-backup/badge.svg)](https://coveralls.io/github/Itonomy/module-backup)

A backup module for Magento 2.x which supports cleaning and compress backup features.

Description
-----------
The backup module is a extension on the magento2 backup module. It includes features as compressing sql files, and cleaning up old backup files.
Compatibility
-------------
- Magento >= 2.0 (Includes 2.2)

This library aims to support and is [tested against][travis] the following PHP
implementations:

* PHP 7.0
* PHP 7.1

enforced in the composer.json

Installation Instructions
-------------------------
Install using composer by adding to your composer file using commands:

1. composer require itonomy/module-backup
2. composer update
3. bin/magento module:enable Itonomy_Backup
4. bin/magento setup:upgrade

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.


Copyright
---------
Copyright (c) 2018 Itonomy B.V.


