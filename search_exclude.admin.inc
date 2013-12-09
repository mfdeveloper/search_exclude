<?php
/**
 * @file
 * Callbacks for new form buttons
 * to run search indexing and
 * new status message
 */

/**
 * Form submission handler for search_admin_settings().
 * 
 * Save content types on 'search_exclude_content_types'
 * admin when 'Save settings' button is pressed
 * 
 */
function search_exclude_submit($form, $form_state) {
    
    $exclude_types = implode(',', $form_state['values']['exclude_types']);
    
    variable_set('search_exclude_content_types', $exclude_types);
    
}

/**
 * Ajax 'Update status' callback.
 * 
 * Check the 'exclude_types' multiselect field values
 * and update the search status text with 'percentage' and
 * 'remaining' values for indexing
 * 
 * @see search_exclude_status()
 * 
 * @return string Status message with new 'percentage' and 'remaining' index without
 *                content types defined in 'exclude_types' multiselect field
 */
function _search_exclude_ajax_update_status($form, $form_state) {
    
    $remaining = 0;
    $total = 0;
    
    $search_excludes = implode(',', $form_state['values']['exclude_types']);
    $status = module_invoke('search_exclude', 'search_status', $search_excludes);
    
    if ($status) {
        $remaining += $status['remaining'];
        $total += $status['total'];
    }
    
    $count = format_plural($remaining, 'There is 1 item left to index.', 'There are @count items left to index.');
    $percentage = ((int)min(100, 100 * ($total - $remaining) / max(1, $total))) . '%';
    $status = '<p><strong>' . t('%percentage of the site has been indexed.', array('%percentage' => $percentage)) . ' ' . $count . '</strong></p>';
    
    $form['status']['status']['#markup'] = $status;
    
    return $form['status']['status'];
}

/**
 * Search exclude batch process functions
 * 
 * Form submission handler for search_admin_settings().
 * Executed by 'Run Indexing' button submit
 * 
 * @ingroup batch
 * 
 * @see _search_exclude_index_batch()
 * @see _search_exclude_index_finished()
 */

function search_exclude_index_submit(&$form, $form_state) {
    
    $exclude_types = implode(',', $form_state['values']['exclude_types']);
    variable_set('search_exclude_content_types', $exclude_types);
    
    $batch = array(
        'title' => t('Regenerating search index'),
        'progress_message' => t('Processed @current out of @total'),
        'error_message' => t('Error to index nodes for search'),
        'operations' => array(
            array('_search_exclude_index_batch', array('search_exclude'))
        ),
        'finished' => '_search_exclude_index_finished',
        'file' => drupal_get_path('module', 'search_exclude') . '/search_exclude.batch.inc'
    );

    batch_set($batch);
}