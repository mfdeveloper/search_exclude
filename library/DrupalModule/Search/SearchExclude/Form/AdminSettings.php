<?php

/**
 * Admin Settings Form to hook_form_alter 
 * for add custom fields
 * 
 * @author Michel Felipe <https://github.com/mfdeveloper>
 */
namespace DrupalModule\Search\SearchExclude\Form;

class AdminSettings {
    
    protected $form;
    protected $formState;

    /**
     * Store the drupal search_settings_form variables
     * on protected attributes
     * 
     * @param array Form drupal renderable array passed by hook_form_alter
     * @param array Form state array passed by hook_form_alter
     */
    public function __construct(&$form,&$form_state){
        
        $this->form = $form;
        $this->formState = $form_state;
    }
    
    /**
     * Modify the form with new fields and buttons
     * for search indexing and update search status
     * text
     */
    public function change(){
        
        //Include file with form callbacks(POST submit, Ajax callback...)
        form_load_include($this->formState, 'inc', 'search_exclude','search_exclude.admin');

        $activeModules = variable_get('search_active_modules',array('node','user'));

        /**
        * Add form elements only if the 'search_exclude' 
        * module exists on 'search_active_modules' variable.
        * See the #1296362 issue on Drupal 7.x core
        * 
        * @link https://drupal.org/node/1296362
        */
        if(module_exists('search_config')){

            form_load_include($this->formState, 'inc', 'search_config','search_config.admin');
        }
        if(array_search('search_exclude', $activeModules,true) !== false){
            $this->_addFields();
        }
        
        //var_dump($this->formState);exit;
        
        return array(
            'form'=>$this->form,
            'form_state'=>$this->formState
        );
        
    }
    
    /**
     * Add custom fields to search_admin_settings
     * form
     * 
     * @see search_exclude_submit()
     * @see search_exclude_index_submit()
     * @see _search_exclude_ajax_update_status() 
     */
    private function _addFields() {
        $excludeTypes = variable_get('search_exclude_content_types','');

        $this->form['status']['exclude_types_group'] = array(
            '#type'=>'fieldset',
            '#title'=>t('Content Types to exclude'),
            '#collapsible'=>true,
            '#collapsed'=>empty($excludeTypes),
            '#description'=>t('Select the content types not will be indexed on <b>cron</b>, <b>drush</b> or manually clicking on: <b>"@btn"</b> button below',array('@btn'=>t('Run Indexing')))
        ); 

        $contentTypes = node_type_get_names();
        if(!empty($contentTypes)){

            $selected = array();

            foreach ($contentTypes as $type => $typeName) {

                $options[$type] = $typeName;

                if(strpos($excludeTypes, $type) !== false){
                    $selected[] = $type;

                }
            }

            $moduleSelect = 'jquery_ui_multiselect_widget';
            $msg = t('<b>Obs:</b> For better "look and feel" for this component, enable the module <a href="@moduleUrl">@module</a>',
                      array(
                        '@module'=>$moduleSelect,
                        '@moduleUrl'=>'https://drupal.org/project/jquery_ui_multiselect_widget'
                      )
                    );

            $selectDesc = !module_exists('jquery_ui_multiselect_widget')?$msg:'';
            $this->form['status']['exclude_types_group']['exclude_types'] = array(
                '#type'=>'select',
                '#title'=>t('Select the Content(s) Type(s)'),
                '#options'=>$options,
                '#default_value'=>$selected,
                '#multiple'=>true,
                '#description'=>$selectDesc
            );
        }

        $this->form['status']['status']['#prefix'] = '<div id="search_status_text">';
        $this->form['status']['status']['#suffix']= '</div>';

        $this->form['status']['exclude_types_group']['update_status'] = array(  
            '#type'=>'button',
            '#value'=>t('Update status'),
            '#executes_submit_callback' => false,
            '#limit_validation_errors' => array(),
            '#submit'=>array(),
            '#ajax'=>array(
                'callback'=>'search_exclude_ajax_update_status',
                'wrapper'=>'search_status_text',
                'effect'=>'fade'
            )
        );

        $this->form['status']['exclude_types_group']['run_indexing'] = array(
            '#type'=>'submit',
            '#value'=>t('Run Indexing'),
            '#submit'=>array('search_exclude_index_submit')
        );

        $this->form['#submit'][] = 'search_exclude_submit';
        
        return $this;
    }
}

?>
