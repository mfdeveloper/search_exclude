-- SUMMARY --

This module extend the search core module with form components 
to exclude content types for search indexing. 
Add a button for run indexing using Batch API.

The code of this module has two "styles":

7.x-1.0 (default drupal style)

This version of module has structured code with global 
functions directly implemented in hooks

7.x-2.0 Namespace style

Object Oriented code with basic autoload on hook_boot(), 
namespaces and closures (require PHP 5.3+).

-- HOW TO USE SEARCH EXCLUDE --

  1. Download and enable this module. 
     Choice the 7.x-1.0 or 7.x-2.0 version (explained above)

  2. Go to admin/config/search/settings on Drupal 7
     administration page,enable and set "Search Exclude Node
     Type" to default search module

  3. Select a content type to exclude of indexing

-- REQUIREMENTS --

  - Drupal Search core module
  - This module works only Drupal 7

-- NEXT IMPLEMENTATIONS --

In future, create one another version to this module 
integrating with Silex PHP Microframework