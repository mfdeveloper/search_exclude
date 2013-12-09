<?php

/**
 * Search Exclude class implementation
 *
 * @author Michel Felipe <https://github.com/mfdeveloper>
 */
namespace DrupalModule\Search;

class SearchExclude {
    
    /**
     * Update index witout content types
     * defined on search_settings_form
     * 
     * @see node_update_index()
     * @see _node_update_index() 
     */
    public function updateIndex() {
        
        $limit = (int)variable_get('search_cron_limit', 100);
  
        $query = "SELECT n.nid,n.title,n.type FROM {node} n LEFT JOIN {search_dataset} d ON d.type = 'node' AND d.sid = n.nid WHERE (d.sid IS NULL OR d.reindex <> 0)";

        $excludeTypes = variable_get('search_exclude_content_types','');

        $args = func_get_args();
        if(isset($args[0])){
            $excludeTypes = $args[0];
        }

        if(!empty($excludeTypes)){
            $excludeTypes = preg_replace('/,/',"','",$excludeTypes);

            $query .= " AND (n.type NOT IN('$excludeTypes'))";
        }

        $query .= ' ORDER BY n.type ASC, n.nid ASC';

        $result = db_query_range($query, 0, $limit, array(), array('target' => 'slave'));

        foreach ($result as $node) {
            _node_index_node($node);
        }
    }
    
    /**
     * Responds to hook_form_alter
     * for search_settings_form
     * 
     * @param array $form Form data passed by Drupal fom api on hook_form_alter
     * @param array $form_state Form state passed by Drupal fom api on hook_form_alter
     * @return DrupalModule\Search\SearchExclude\Form\AdminSettings 
     */
    public function form(&$form,&$form_state) {
        
        return new SearchExclude\Form\AdminSettings($form,$form_state);
    }
}
?>