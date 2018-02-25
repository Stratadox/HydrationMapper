# Hydration Mapper

[![Implements](https://img.shields.io/badge/interfaces-github-blue.svg)](https://github.com/Stratadox/HydrationMapperContracts)
[![Build Status](https://travis-ci.org/Stratadox/HydrationMapper.svg?branch=master)](https://travis-ci.org/Stratadox/HydrationMapper)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/HydrationMapper/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/HydrationMapper?branch=master)
[![Infection Minimum](https://img.shields.io/badge/msi-100-brightgreen.svg)](https://travis-ci.org/Stratadox/HydrationMapping)
[![PhpStan Level](https://img.shields.io/badge/phpstan-7-brightgreen.svg)](https://travis-ci.org/Stratadox/HydrationMapping)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/HydrationMapper/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/HydrationMapper/?branch=master)
[![Maintainability](https://api.codeclimate.com/v1/badges/6370c294ff33dec95c25/maintainability)](https://codeclimate.com/github/Stratadox/HydrationMapper/maintainability)
[![Latest Stable Version](https://poser.pugx.org/stratadox/hydration-mapper/v/stable)](https://packagist.org/packages/stratadox/hydration-mapper)
[![License](https://poser.pugx.org/stratadox/hydration-mapper/license)](https://packagist.org/packages/stratadox/hydration-mapper)

Mapping builder for hydration purposes; maps array or array-like data structures to 
object properties, in order to assemble the objects that model a business domain.

# Usage sample

```php
<?php

$hydrator = Mapper::forThe(Book::class)
     ->property('title', Has::one(Title::class)
        ->with('title')
     )
     ->property('isbn', Has::one(Isbn::class)
         ->with('code', In::key('id'))
         ->with('version', Call::the(function ($data) {
             return strlen($data['id']);
         }))
     )
     ->property('author', Has::one(Author::class)
         ->with('firstName', In::key('author_first_name'))
         ->with('lastName', In::key('author_last_name'))
     )
     ->property('contents', Has::many(ChapterProxy::class)
         ->containedInA(Chapters::class)
         ->loadedBy(new ChapterLoaderFactory)
     )
     ->property('format')
     ->finish();

```

# Installation

Install using composer:

`composer require stratadox/hydration-mapper`


# More details

For more information, view the [Hydrate repository](https://github.com/Stratadox/Hydrate)
