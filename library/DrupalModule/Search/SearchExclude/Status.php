<?php

/**
 * Status Message customizations and actions
 *
 * @author Michel Felipe <https://github.com/mfdeveloper>
 */
namespace DrupalModule\Search\SearchExclude;

class Status implements DbQueryStatusTag{
    
    /**
     * Get the status message without the content types
     * choiced by user on search_settings_form
     * 
     * Copied from node_search_status() and modified
     * 
     * @see node_search_status()
     * @see search_exclude_query_node_search_status_total_alter()
     * @see search_exclude_query_node_search_status_remaining_alter()
     * @return string Status message without content types store on 
     *                'search_exclude_content_types' variable or passed by param 
     */
    public function getData() {
        
        $excludes = variable_get('search_exclude_content_types');
        if(empty($excludes)){

            $result = module_invoke('node', 'search_status');
            
        }

        $total = db_select('{node}','n')->fields('n',array('nid'));

        $orConditions = db_or();
        $orConditions->condition('d.reindex','0','<>')
                    ->isNull('d.sid');

        $remaining = db_select("{node}",'n')->fields('n',array('nid'));

        $excludeTypes = func_get_args();

        /*
        * If content types for exclude is passed by arguments, 
        * add for query's condition 
        */
        if(!empty($excludeTypes)){

            if(!empty($excludeTypes[0])){

                if(strpos($excludeTypes[0],',') !== false){

                    $excludeTypes = explode(',', $excludeTypes[0]);
                }
                
                $total->condition('n.type',  $excludeTypes,'NOT IN');
                $remaining->condition('n.type',  $excludeTypes,'NOT IN');
            }
        }else{

            //Add tags only if content types for exclude not passed by arguments for this function
            $total->addTag('node_search_status_total');
            $remaining->addTag('node_search_status_remaining');
        }

        $total =  $total->countQuery()
                        ->execute();

        $total = $total->fetchObject()->expression;

        $remaining->leftJoin('{search_dataset}','d',"d.type = 'node' AND d.sid = n.nid");
        $remaining->condition($orConditions);

        $result = $remaining->countQuery()->execute();
        $result = $result->fetchObject()->expression;

        return array('remaining' => $result, 'total' => $total);
    }
    
    /**
     * Get status message updated with 'content types'
     * excluded
     * 
     * @param string $excludeTypes Content types for exclude in format 'content_type_one,content_type_two'...
     * @return string Status message with new total and remaining for indexing 
     */
    public function getMessage($excludeTypes) {
        
        $remaining = 0;
        $total = 0;

        $searchExcludes = implode(',',$excludeTypes);
        
        $status = $this->getData($searchExcludes);

        if($status){
            $remaining += $status['remaining'];
            $total += $status['total'];
        }

        $count = format_plural($remaining, 'There is 1 item left to index.', 'There are @count items left to index.');
        $percentage = ((int)min(100, 100 * ($total - $remaining) / max(1, $total))) . '%';
        $status = '<p><strong>' . t('%percentage of the site has been indexed.', array('%percentage' => $percentage)) . ' ' . $count . '</strong></p>';

        return $status;
    }

    /**
     * Add custom condition to $query object
     * 
     * @param QueryAlterableInterface|SelectQuery $query
     * @see $this->_modifyQuery 
     */
    public function changeRemaining(&$query) {
        $this->_modifyQuery($query);
    }
    
    /**
     * Add custom condition to $query object
     * 
     * @param QueryAlterableInterface|SelectQuery $query
     * @see $this->_modifyQuery 
     */
    public function changeTotal(&$query) {
        $this->_modifyQuery($query);
    }
    
    /**
     * Add custom condition to Drupal query object.
     * This method is trigger on tags defined on 
     * hook_query_TAG_alter functions
     * 
     * @param QueryAlterableInterface|SelectQuery The Drupal query object for modify
     */
    private function _modifyQuery(&$query){
        
        $excludeTypes = variable_get('search_exclude_content_types');

        if(!empty($excludeTypes)){
            $query->condition('n.type',  explode(',', $excludeTypes),'NOT IN');
        }
    }
}

?>