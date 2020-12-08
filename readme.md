Kuusamo VLE
===========

[![Latest Stable Version](https://poser.pugx.org/kuusamo/core/v)](//packagist.org/packages/kuusamo/core)
[![Total Downloads](https://poser.pugx.org/kuusamo/core/downloads)](//packagist.org/packages/kuusamo/core)
[![License](https://poser.pugx.org/kuusamo/core/license)](//packagist.org/packages/kuusamo/core)
[![Build Status](https://travis-ci.org/kuusamo/core.svg?branch=master)](https://travis-ci.org/kuusamo/core)

A learning management system (LMS) that uses API-friendly JSON blocks to build lessons.


Roadmap
-------

* Unenrol a student
* Edit a student enrollment
* Delete a user

* Admin preview for course
* Travis CI migration
* Schema install
* Footer links, privacy policy
* Database migrations
* Sticky lesson menu
* SMTP
* Drip content
* Read The Docs
* Storage folders that don't exist on file system
* Support duplicate file names
* S3 integration
* Cache busting assets
* Htaccess improvements
* Use streams for Crop
* Audio preloading

Relationship cascades

* User
* Image (awarding body, course, image block)


Development
-----------

Run the tests:

    ant

Compile the JavaScript:

    gulp webpack

Compile the CSS:

    sass sass:public/styles

Update the database schema:

    vendor/bin/doctrine orm:schema-tool:update

Symlink assets from a project

    vendor/bin/kuusamo dev-assets
    vendor/bin/kuusamo restore-assets # when done
