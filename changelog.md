Changelog
=========

1.15
----
* Course transfers (experimental feature)
* Pre-generate Doctrine proxies

1.14
----
* Course certificates can be enabled/disabled
* Improve labelling of course privacy
* Allow admins to download student certificates
* Admins can delete users
* Decaching for CSS and JavaScript assets
* Folder validator now check for special characters
* Users can be promoted to admins
* Images are now uploaded directly in Lesson Editor

1.13.2
------
* Increasing minimum PHP version to `7.4`

1.13.1
------
* Fixing version number

1.13
----
* Course privacy setting allows open-registration courses

1.12
----
* Fetch users API end-point

1.11.3
------
* Fixing Doctrine's cache peer dependency breaking change

1.11.2
------
* Remove additional full stop from homepage (when not an admin)
* Improved handling of magic link tokens
* Upgrade dependencies

1.11.1
------
* Updating text for magic link confirmation screen
* Only verify the existing  password on change if one exists

1.11
----
* Admins can now see all courses from the homepage
* Admins can now see enrolment information for a student
* Admins can manually mark a course as complete
* Toggling lesson completion status in the admin now recalculates progress
* Last login date is now available on the list of users
* Fixed display issue with no courses in admin dashboard
* Fixed bug with most recent lesson not included in progress calculation

1.10.1
------
* Disable doctrine cache

1.10
----
* `schema` add support for longer media types on files

1.9
---
* Improvements on how certificates handle multiple accreditations
* Preview links on list of courses in admin
* Add preview tab to admin course view
* Add Kussamo version and PHP version to admin info
* Add a phpinfo() screen
* Paginate users, students and images in admin
* Autocomplete for enrolling students
* `schema` Footer text can now be set in the admin
* Support for custom header tags

1.8.1
-----
* Handle blobs as well as streams in image crops

1.8
---
* Display filename if name is not available on downloads
* Audio element now has a responsive width
* Admins can now preview the course dashboard
* Courses are now ordered alphabetically in admin
* `schema` Added welcome text to courses

1.7
---
* New styling for downloads and improvements to lesson view
* Files admin now has an edit screen for names
* Database migration support
* `schema` Files can now be organised into folders
* Local storage will now create folders that do not exist

1.6
---
* Support custom storage providers

1.5
---
* Lesson name box in Course Manager is now wider
* Focus jumps back to lesson box when creating a lesson
* Jump to module when closing the lesson editor

1.4
---
* Use email on homepage if name is not available
* Add name to setup screen
* Show "no rows" message when there is no data
* Set default status on course manager
* Delete module functionality
* Delete lesson functionality
* Courses with modules can now be deleted
* Better handling of award body deletion
* File usage report and handling of deletion
* Unenrol user functionality
* Admin preview of lessons
* Support duplicate filenames for file uploads
* Support duplicate filenames for image uploads
* View image in admin area

1.3
---
* Setup screen to install the first user
* Automatic asset installation

1.2
---

* Audio is now explictly not preloaded
* Multiple-choice questions
* Graded assessments
* Manual marking for students
* API added with test end-point
* User create and enrol API
* Handle JSON requests in exception controller
* Handle duplicate email address errors
* Handle duplicate course URI errors
