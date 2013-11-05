Drupal 7 - Search Exclude Module
==============

It's a Drupal 7 module that extend the search core module with form components to exclude content types for search indexing. Add to a button for run indexing using batch process api

Screenshots
-----------

![Module Search Default](https://github.com/mfdeveloper/search_exclude/images/search_exclude_default_module.png)

![Content Types to exclude](https://github.com/mfdeveloper/search_exclude/images/search_exclude_content_types.png)

Usage
-----

1.  Clone this repository and move files from the **drupal_style** folder to sites/all/modules on your Drupal 7 directory installation
2.  Go to admin/modules, search the **Search Exclude Node Type** module and enable it.
3.  Go to admin/config/search/settings on Drupal 7 administration page and see the search modifications showed in **Screenshots** section
4.  Enjoy it!
 

Next implementations
--------------------

- [ ] Add the **namespace_style** folder with Object Orientation implementation of module compatible with PHP 5.3+
- [ ] More .test files with unit tests using SimpleTest API
- [ ] **Silex** framework integration
