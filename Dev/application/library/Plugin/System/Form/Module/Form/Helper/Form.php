<?php
namespace Module\Form\Helper;


/**
 * Widget Helper
 * Functions useful for building HTML forms with less code
 * 
 * @package system
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @link http://systemframework.com/
 */
class Form extends \System\View\Helper\HelperAbstract
{
    public function load(array $params=array())
    {
        /*
        $data = array(
            array(
                'label'         => 'Project Name',
                'input'         => 'text',
                'placeholder'   => 'Enter a project name',
                'description'   => null,
                'value'         => null,
                'options'       => null,
                'required'      => true,
                'validation'    => FORM_VALIDATE_TYPE_STRING,
                'dimension'     => FORM_INPUT_DIMENSION_TYPE_FULL,
                'styles'        => array(
                    'wrapper'       => null,
                    'input'         => null
                ),
                'append'        => null,
                'prepend'       => null,
                'weight'        => 25
            )
        );
        */
        $formData = $this->kernel->events('ui')->filter('load_form', array(
            'params'  => $params,
            'fields'    => array()
        ));
        $formInputs = $formData['fields'];
        uasort($formInputs, function($a, $b) {
            if($a['weight'] == $b['weight']) {
                return 0;
            } else {
                return ($a['weight'] < $b['weight']) ? -1 : +1;
            }
        });
        $formData['fields'] = $formInputs;
        return $formData;
    }

    public function render_modal($form_name, array $data=array(), $trigger=null)
    {
        $form = $this->load(array(
            'name'  => $form_name
        ));
        foreach($form['fields'] as $key => $value) {
            if(isset($data[$key])) {
                $form['fields'][$key]['value'] = $data[$key];
            }
        }
        if($trigger != null) {
            $form['params']['trigger'] = $trigger;
        }
        if(!isset($form['params']['trigger'])) {
            $form['params']['trigger'] = '.action-' . str_replace('.', '-', $form_name) . '-trigger';
        }

        //___debug($form);
        include __DIR__ . '/forms/standard-modal.html.php';
    }

    public function render($form_name, $template, array $params=array())
    {
        $form = $this->load(array_merge(array(
            'name'  => $form_name
        ), $params));
        include $template;
    }

    protected function field_validate($form, $data, $key, $field, $errors)
    {
        if(!isset($errors[$key]) && isset($field['required']) && $field['required'] == true) {
            if(!isset($data[$key]) || strlen(trim($data[$key]))==0) {
                $errors[$key] = 'Value for "' . $field['label'] . '" is required';
            }
        }
        if(!isset($errors[$key]) && isset($field['required_if']) && count($field['required_if']) > 0) {
            foreach($field['required_if'] as $rkey => $rvalue) {
                if(isset($data[$rkey]) && $data[$rkey] == $rvalue) {
                    if(!isset($data[$key]) || strlen(trim($data[$key]))==0) {
                        $errors[$key] = 'Value for "' . $field['label'] . '" is required';
                    }
                }
            }
        }
        if(isset($data[$key])) {
            if (!isset($errors[$key]) && isset($field['validation'])) {
                //___debug(array($data, $form, $key));
                if (isset($data[$key])) {
                    foreach ($field['validation'] as $validation_code) {
                        $validations = \Kernel()->events('form')->filter('validate_data', array(
                            'validation' => $validation_code,
                            'field_key' => $key,
                            'data' => $data[$key],
                            'errors' => array()
                        ));
                        //___debug($validations);
                        if (count($validations['errors']) > 0) {
                            $errors = array_merge($errors, $validations['errors']);
                        }
                    }
                }
            }
            if (!isset($errors[$key]) && isset($field['validation_callback'])) {
                $callback_valid = call_user_func($field['validation_callback'], $data);
                if (is_array($callback_valid)) {
                    if (is_array($callback_valid[0])) {
                        $errors['__processing_error'] = $callback_valid[0][0];
                    } else {
                        $errors[$key] = $callback_valid[0];
                    }
                }
            }
        }
        return $errors;
    }

    protected function validate($form, $data)
    {
        $errors = array();
        foreach($form['fields'] as $key => $field) {
            $errors = $this->field_validate($form, $data, $key, $field, $errors);
            if(isset($field['children']['source'])) {
                foreach($field['children']['source'] as $ckey => $cfield) {
                    foreach($cfield as $cckey => $ccfield) {
                        $errors = $this->field_validate($form, $data, $cckey, $ccfield, $errors);
                    }
                }
            }
            if(isset($field['observe']['children'])) {
                foreach($field['observe']['children'] as $ckey => $cfield) {
                    foreach($cfield as $cckey => $ccfield) {
                        $errors = $this->field_validate($form, $data, $cckey, $ccfield, $errors);
                    }
                }
            }
        }

        if(count($errors) == 0 ) {
            return true;
        }
        return $errors;
    }

    public function process_submission(array $data = array())
    {
        $form = $this->load(array(
            'name'  => $data['__form_name']
        ));
        $validation = $this->validate($form, $data);
        if(is_array($validation)) {
            return array(
                'errors' => $validation
            );
        }

        //___debug($data);

        $result = $this->kernel->events('ui')->filter('process_form_submission', array(
            'params'    => array(
                'name'  => $data['__form_name']
            ),
            'form'   => $form,
            'values' => $data
        ));

        if(isset($result['errors'])) {
            return array(
                'errors' => array(
                    '__processing_error' => $result['errors']
                )
            );
        }
        return array(
            'completed' => true,
            'notify'    => isset($result['notify'])?$result['notify']:null
        );
    }

    public function load_child($form_name, $field, $child_key)
    {
        //___debug(array($form_name, $field, $child_key));
        $form = $this->load(array(
            'name'  => $form_name
        ));
        if(!isset($form['fields'][$field]['children']['source'][$child_key])) {
            return array();
        }
        return array(
            'params' => $form['params'],
            'fields' => $form['fields'][$field]['children']['source'][$child_key]
        );
    }
}