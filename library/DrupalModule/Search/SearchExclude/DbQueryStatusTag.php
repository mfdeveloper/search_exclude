<?php

/**
 * Query Search Status tag hooks that need
 * customizations
 * 
 * @author Michel Felipe <https://github.com/mfdeveloper>
 */
namespace DrupalModule\Search\SearchExclude;

interface DbQueryStatusTag {
    
    /**
     * Implement this method for a custom
     * query Drupal object to change total
     * search status
     * 
     * @param QueryAlterableInterface|SelectQuery
     */
    public function changeTotal(&$query);
    
    /**
     * Implement this method for a custom
     * query Drupal object to change remaining
     * search status
     * 
     * @param QueryAlterableInterface|SelectQuery
     */
    public function changeRemaining(&$query);
}
?>