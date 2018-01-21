# Hydration Mapper

[![Build Status](https://travis-ci.org/Stratadox/HydrationMapper.svg?branch=master)](https://travis-ci.org/Stratadox/HydrationMapper)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/HydrationMapper/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/HydrationMapper?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/HydrationMapper/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/HydrationMapper/?branch=master)

Mapping builder for hydration purposes; maps array or array-like data structures to 
object properties, in order to assemble the objects that model a business domain.

# Usage sample

```php
<?php

$mapping = Mapper::forThe(Book::class)
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
         ->containedInA(Contents::class)
         ->loadedBy(new ChapterLoaderFactory)
     )
     ->property('format')
     ->map();

```

# Installation

Install using composer:

`composer require stratadox/hydration-mapper`