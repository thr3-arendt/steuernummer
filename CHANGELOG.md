# Changelog

All notable changes to `steuernummer` will be documented in this file.

## v0.1.0 PHP 7.2 compatibility

- removes PHP features incompatible with 7.2
- removes pest due to incompatibility
- rewrites tests for phpunit

## From original branch

- Normalizes, denormalizes and validates German tax numbers
- Returns human readable errors for invalid input
- Includes multiple helpers with `functions.php` (PSR-4 autoladed)
