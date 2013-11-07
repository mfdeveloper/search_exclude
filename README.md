Drupal 7 - Search Exclude Module
==============

It's a Drupal 7 module that extend the search core module with form components to exclude content types for search indexing. Add to a button for run indexing using batch process api

Screenshots
-----------

####Select the module that will manage the search

![Module Search Default](https://github.com/mfdeveloper/search_exclude/raw/master/images/search_exclude_default_module.png)

####Remove content types for indexing
![Content Types to exclude](https://github.com/mfdeveloper/search_exclude/raw/master/images/search_exclude_content_types.png)

Usage
-----

1.  Clone this repository and choice one the follow branches:
    
     <dl>
       <dt>master (default drupal style)</dt>
       <dd>This version of module has structured code with global functions directly implemented in hooks</dd>
       <dt>namespace_style</dt>
       <dd>Object Oriented code with basic autoload on ``hook_boot()``, namespaces and closures (require PHP5.3+)</dd>
     </dl>

2.  Move this files to **sites/all/modules on your Drupal 7** directory installation
2.  Go to admin/modules, search the **Search Exclude Node Type** module and enable it.
3.  Go to admin/config/search/settings on Drupal 7 administration page and see the search modifications showed in **Screenshots** section
4.  Enjoy it!
 

Next implementations
--------------------

- [X] Add the **namespace_style** branch with Object Orientation implementation of module compatible with PHP 5.3+ (Updated on 07/11/2013)
- [ ] More .test files with unit tests using SimpleTest API
- [ ] **Silex** framework integration
