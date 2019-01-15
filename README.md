Fastred
=======

> Procedural micro-framework for PHP server and JavaScript frontend.

The general purpose is to provide:

- Easy-to-use overridable global functions and constants.
- Appropriate functions available both at server and client side.
- Clear [naming convention](#naming-convention).
- JSON-like variable types.
- Guideline to organize modules in your project.

## Important notice

Fastred keeps programming as short and simple as possible and tries to exclude overhead from complex programming
constructions, like classes and namespaces. All functions are global.

**This is not always what you may need.**

If you make something big and scalable with pretty large team you'd better use some other framework.
Composer cannot autoload global functions, so with Fastred you'll have to use `fastredRequire()` to load needed modules (see [Usage](#usage)).

However, Fastred can be easily combined with any other framework or coding style if you want to use it partially.
It has some useful basic modules, like `img`, `pgn` or `sql` and can be extended with additional libraries.

## Definitions

### Module

File of code that contains global functions and constants and follows [naming convention](#naming-convention).

### Universal module

Module that is implemented both in PHP and JavaScript in whole or in part.

## PHP

### Requirements

* [PHP 5.3+](http://www.php.net/downloads.php)

### Installation

You can use [composer][], so Fastred will be included with `autoload.php`:

    composer require dehero/fastred

Or you can extract Fastred distributive somewhere into your project's directory, then:

```php
require 'path/to/fastred.php'
```

### API

Fastred provides two basic functions:

#### fastredLibrary($path)

#### fastredRequire($module1, $module2, ...)

### Usage

Provide one or more directories to search modules in. Basic modules are included with `fastred.php`

```php
fastredLibrary(__DIR__ . '/modulesA')
fastredLibrary(__DIR__ . '/modulesB')
```

```php
fastredRequire($module1, $module2, ...)
fastredRequire($module3)
```

```php
module1Function($arg);
module2Function($arg);
echo MODULE2_CONST;
```

### Extending



## JavaScript

### Requirements

* Webpack

### Usage

    npm install fastred

```js
fastredLibrary(require.context('./js'))
```

#### fastredLibrary()

#### fastredRequire(module1, module2, ...)

## Naming convention

### PHP

```php
thisIsFunction($paramA, $paramB)

object->someProperty

$someVariable

THIS_IS_CONSTANT
```

### JavaScript

```js
thisIsFunction(paramA, paramB)

obj.someProperty

someVariable

THIS_IS_CONSTANT
```

- Function names should always be written in camelcase. Names of functions start with module ident, like `img*`, `url*` or `pageData*`.
- Main function of the module is named by module ident itself, like `url()`.
- Functions with names containing words `Get`, `Of`, like `urlGetCurrent()` or `urlOfPage()` pass main
result through their return.
- Functions with names containing verb in present simple, like `emailIsValid()` or `fileExists()`, usually return boolean result.
- Functions with name containing `To` or `From` are for conversion.
- Other functions create, declare or change some existing things.

Object properties should always use camelcase code style, like `$fastred->thisIsProperty`.
If property name is too long being written in single camelcase phrase, that's a
sign that properties should be nested, like `$fastred->app->page->data`.

## Variable types

There are two reasons why objects but not arrays are used for stroring complex
data in Fastred:

1.  `$data->key->subkey` is shorter and easier to write, than
    `$data['key']['subkey']`.

2.  Objects are passed by references, so PHP doesn't need to waste time cloning
    object data for functions.

To check if an object has any properties it's best to use function
`objHasProperties($object)` from `obj` module.

## Basic modules

Module      | Universal | Purpose
------------|:---------:|-----------------------------------------------------------------------------------------------
app         |           | Application lifecycle, routing, error handling
arr         | Yes       | Arrays
cache       | Yes       | Caching values in memory just for current script
css         |           |
datetime    | Yes       | Working with date and time
dir         | Yes       | Directories
email       |           | Sending email
file        |           |
img         |           | Images
json        | Yes       | JSON
obj         | Yes       | Objects (reference-passed associative arrays)
page        |           |
path        | Yes       |
pgn         |           | Pagination
script      |           |
sql         |           | Constructing SQL expressions
str         | Yes       | Strings
url         |           | Constructing and parsing URLs
var         | Yes       | Variables of all types

## Tips

### Usage with Pug

Fastred can be useful with [pug][]:

- Create `.pug` template using universal Fastred functions for presentation code.
- Compile template to PHP with [pug-php][], having `expressionLanguage` set to `js`.
- Compile template to JavaScript with [pug][] (you can use [pug-loader][] for [webpack][] or other bundler).

[composer]: https://getcomposer.org/ "The PHP package manager"
[pug]: https://pugjs.org/api/getting-started.html "High-performance template engine"
[pug-php]: https://github.com/pug-php/pug "Pug-to-PHP converter"
[pug-loader]: https://github.com/pugjs/pug-loader "Pug loader for webpack"
[webpack]: https://webpack.js.org/ "JavaScript bundler"
