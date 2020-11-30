Kuusamo VLE
===========

[![Latest Stable Version](https://poser.pugx.org/kuusamo/core/v)](//packagist.org/packages/kuusamo/core)
[![Total Downloads](https://poser.pugx.org/kuusamo/core/downloads)](//packagist.org/packages/kuusamo/core)
[![License](https://poser.pugx.org/kuusamo/core/license)](//packagist.org/packages/kuusamo/core)
[![Build Status](https://travis-ci.org/kuusamo/core.svg?branch=master)](https://travis-ci.org/kuusamo/core)

A learning management system (LMS) that uses API-friendly JSON blocks to build lessons.


Roadmap
-------

Graded assessments

* Build backend end-point and submit the score, then use result instead of dummy data
* Add more information to result?



* Lesson navigation bar spacing
* React "next lesson" button on final lesson
* @todo in es/constants.js
* Database migrations
* Sticky lesson menu
* Assessments
* Manual marking
* Pass mark for non-graded lessons

* SMTP
* Drip content
* Relationship cascades
* Read The Docs
* Storage folders that don't exist on file system
* Support duplicate file names
* S3 integration
* Cache busting assets
* Htaccess improvements
* Use streams for Crop
* Audio preloading


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
