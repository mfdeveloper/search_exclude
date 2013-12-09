<?php
/**
 * @file
 * Batch callbacks to run
 * search indexing 
 */

/**
 * Run indexing in batch process
 * 
 * @param string $module Module name with hook_update_index
 * @param type $context Context defined by batch_set() function
 * 
 * @see search_exclude_update_index()
 * @see search_exclude_search_status()
 */
function _search_exclude_index_batch($module, &$context) {
    
    //Fix the "Request Time out" problem in batch process
    set_time_limit(0);
    
    module_invoke($module, 'update_index');
    $status = module_invoke('search_exclude', 'search_status', variable_get('search_exclude_content_types'));
    
    $completed = $status['total'] - $status['remaining'];
    $context['finished'] = empty($status['total']) ? 1 : $completed / $status['total'];
    $context['message'] = t('Now indexing content. Remaining @remaining of @total', array('@remaining' => $status['remaining'], '@total' => $status['total']));
}

/**
 * Execute when _search_exclude_index_batch() is finished
 * 
 * @see _search_exclude_index_batch()
 */
function _search_exclude_index_finished($success, $results, $operations) {
    
  if ($success) {
      drupal_set_message(t('Indexed all @items records.', array('@items' => count($results))));
  }
  else {
      $error_operation = reset($operations);
      
      $message = t('Error to index nodes for search while processing @operation', array('@operation' => $error_operation[0], '@args' =>  print_r($error_operation[0], TRUE)));
      drupal_set_message($message, 'error');
  }  
}