<?php

class Form {

	private $fields = array();
	private $form;
	
	// FORM OPTION VARIABLES
	private $method = "post";
	private $action;
	private $form_class;
	private $id;
	private $name;
	private $enctype;
	
	
	// SETTER METHODS
	public function set_method($method) {
		$this->method = $method;
	}
	public function set_action($action) {
		$this->action = $action;
	}
	public function set_class($form_class) {
		$this->form_class = $form_class;
	}
	public function set_id($id) {
		$this->id = $id;
	}
	public function set_name($name) {
		$this->name = $name;
	}
	public function set_enctype($enctype) {
		$this->enctype = $enctype;
	}
	
	
	public function add_input($type, $fieldname, $label = "", $value = "", $additional_info = "") {
		if($type[0]=='*') {
			// field is required
			$required = true;
			// remove the asterisk
			$type = str_replace('*', '', $type);
		} else {
			$required = false;
			// remove any rogue asterisks, just in case
			$type = str_replace('*', '', $type);
		}
		
		if($label != "") {
			$label = $this->build_label($fieldname, $label, $required);
		}
		
		$input = "\n".'<input type="'.$type.'" name="'.$fieldname.'" id="'.$fieldname.'" value="'.htmlentities($value).'" '.$additional_info.' />';
		
		$this->fields[] = array('label'=>$label, 'input'=>$input);
	}
	
	private function build_label($fieldname, $label, $required = false) {
		$label_html = '<label for="'.$fieldname.'">';
		$label_html .= $label;
		if($required) {
			$label_html .= ' <span class="required">*</span>';
		}
		$label_html .= '</label>';
		
		return $label_html;
	}
	
	
	public function build_form() {
		$this->form = '<form ';
		if(!empty($this->form_class)) {
			$this->form .= 'class="'.$this->form_class.'" ';
		}
		if(!empty($this->id)) {
			$this->form .= 'id="'.$this->id.'" ';
		}
		if(!empty($this->name)) {
			$this->form .= 'name="'.$this->name.'" ';
		}
		if(!empty($this->enctype)) {
			$this->form .= 'enctype="'.$this->enctype.'" ';
		}
		$this->form .= 'method="'.$this->method.'" ';
		$this->form .= 'action="'.$this->action.'" ';
		$this->form .= '>';
		
		foreach($this->fields as $field) {
			//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			// BUILD THIS OUT !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			$this->form .= '<div class="row">';
			$this->form .= $field['label'];
			$this->form .= $field['input'];
			$this->form .= '</div>';
		}
		
		$this->form .= "</form>";
		
		return $this->form;
	}
}

?>