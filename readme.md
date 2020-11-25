Kuusamo VLE
===========

[![Latest Stable Version](https://poser.pugx.org/kuusamo/core/v)](//packagist.org/packages/kuusamo/core)
[![Total Downloads](https://poser.pugx.org/kuusamo/core/downloads)](//packagist.org/packages/kuusamo/core)
[![License](https://poser.pugx.org/kuusamo/core/license)](//packagist.org/packages/kuusamo/core)
[![Build Status](https://travis-ci.org/kuusamo/core.svg?branch=master)](https://travis-ci.org/kuusamo/core)

A learning management system (LMS) that uses API-friendly JSON blocks to build lessons.


To do list
----------

* Database install / migrations
* Audio is loading the entire file through PHP and killing everything


* Add your own logo
* Add your colour scheme
* Better mark as completed


Roadmap
-------

* Sticky lesson menu
* Assessments
* Drip content
* Relationship cascades
* Read The Docs
* Storage folders that don't exist on file system
* Support duplicate file names
* S3 integration
* Cache busting assets
* Htaccess improvements


Development
-----------

Useful functions

    ant
    gulp webpack
    vendor/bin/doctrine orm:schema-tool:update
    sass sass:public/styles

If you are developing in a project, symlink the CSS and JS

    vendor/bin/kuusamo dev-assets # symlink
    vendor/bin/kuusamo assets # when done

Colours

https://coolors.co/03045e-023e8a-0077b6-0096c7-00b4d8-48cae4-90e0ef-ade8f4-caf0f8
