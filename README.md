# Octo - Content Management System

## Using Octo for a new site
You can find a working example site in the [Octo Skeleton](https://github.com/Block8/Octo-Skeleton) project.

To get started:

* Clone: `git clone git@github.com:Block8/Octo-Skeleton.git <your site name>`
* Move into your new project directory: `cd <your site name>`
* If you want to try out the example site:
  * Create a database and import into it the content from `octo-skeleton.sql`
  * Modify `siteconfig.php` to point to that database
* If you want to create a new site:
  * Remove the .git folder and create as a new repo: `rm -Rf .git && git init`
  * Modify the `siteconfig.php` file as necessary for your project
  * Rename the `Example` namespace and modify the code within it for your project

## Dependencies

### Block 8

### Third Party
Octo would not be possible without the help of the following open source projects:

#### Back-end
* [b8 framework](https://github.com/block8/b8framework) by Block 8 (@block8)
* [Twig](https://github.com/twigphp/Twig) by Fabien Potencier (@fabpot)
* [Symfony Console](https://github.com/symfony/console) by Symfony (@symfony)
* [Phinx](https://github.com/robmorgan/phinx) by Rob Morgan (@robmorgan)
* [PHP 5.5 Password Compat](https://github.com/ircmaxell/password_compat) by Anthony Ferrara (@ircmaxell)
* [Pheanstalk](https://github.com/pda/pheanstalk) by Paul Annesley (@pda)
* [Google API Client](https://github.com/google/google-api-php-client) by Google (@google)
* [Twitter PHP library](https://github.com/dg/twitter-php) by David Grudl (@dg)

#### Front-end
* [Bootstrap CSS](http://getbootstrap.com/) by Mark Otto (@mdo) & Jacob (@fat)
* [Admin LTE](https://github.com/almasaeed2010/AdminLTE) by Abdullah Almsaeed (@almasaeed2010)
* [Select2](https://github.com/select2/select2) by Kevin Brown (@kevin-brown) and Igor Vaynberg (@ivaynberg)
* [CKEditor](http://ckeditor.com/) by CKSource (@ckeditor)