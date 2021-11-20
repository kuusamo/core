Kuusamo VLE
===========

[![Latest Stable Version](https://poser.pugx.org/kuusamo/core/v)](//packagist.org/packages/kuusamo/core)
[![Total Downloads](https://poser.pugx.org/kuusamo/core/downloads)](//packagist.org/packages/kuusamo/core)
[![License](https://poser.pugx.org/kuusamo/core/license)](//packagist.org/packages/kuusamo/core)
[![Build Status](https://app.travis-ci.com/kuusamo/core.svg?branch=master&status=passed)](https://app.travis-ci.com/github/kuusamo/core)

A learning management system (LMS) that uses API-friendly JSON blocks to build lessons.


Roadmap
-------

* Optimise Doctrine
* Manage user roles
* Edit and delete folders
* Can we merge images into files?
* Vimeo video is not responsive (YouTube?)
* Video duration
* Image usage report
* Sticky lesson menu
* Use streams for Crop
* Audio preloading

Relationship cascades

* User (roles)
* Image (awarding body, course, image block)

Paging and search

* Students

Autocomplete

* Enrollment (make prettier, ES6)
* Files in blocks
* Images in blocks
* Images attached to objects


Development
-----------

Run the tests:

    ant

Compile the JavaScript:

    gulp webpack

Compile the CSS:

    npm run sass

Update the database schema:

    vendor/bin/doctrine-migrations diff
    vendor/bin/doctrine-migrations migrate

Symlink assets from a project

    vendor/bin/kuusamo dev-assets
    vendor/bin/kuusamo restore-assets # when done

Releasing

1. Update `changelog`
2. Update version number in `app.php`
3. `git tag -a x.x.x -m "Version x.x.x"`
