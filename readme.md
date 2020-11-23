Kuusamo VLE
===========

A learning management system (LMS) that uses API-friendly JSON blocks to build lessons.


To do list
----------

* Better select dropdown icon - https://css-tricks.com/styling-a-select-like-its-2019/
* Database install / migrations
* Assessments
* Certificate generation
* SMTP integration
* Buttons / inline forms are still not perfect


Roadmap
-------

* Website

* Mailgun integration
* Storage folders that don't exist on file system
* Support duplicate file names
* Themes
* Favicon
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
