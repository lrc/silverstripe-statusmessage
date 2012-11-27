<?php
class StatusMessage extends Extension {

	/**
	 * Types of status message
	 */
	const STATUS_SUCCESS = 'success';
	const STATUS_INFO    = 'info';
	const STATUS_WARNING = 'warning';
	const STATUS_ERROR   = 'error';
	
	private $messages = array();
	
	/**
	 * Return an array of all messages
	 */
	public function Messages($namespace = 'default') {
		$this->getMessagesFromSession($namespace);
		if (isset($this->messages[$namespace])){
			$messages = new ArrayData(array('Messages'=>$this->messages[$namespace]));
			return $messages->renderWith('StatusMessage');
		}
		return null;
	}
	
	private function getMessagesFromSession($namespace = 'default') {
		if ( ! isset($this->messages[$namespace]) ) {
			$messages = Session::get("StatusMessages.$namespace");
			if ($messages) {
				array_walk($messages, function(&$el){$el = new ArrayData($el);});
				$this->messages[$namespace] = new ArrayList($messages);
			}
		}
		Session::clear("StatusMessages.$namespace");
	}
	
	/**
	 * Set a new session message.
	 * 
	 * @param string $message The message to set.
	 * @param string $type [optional] The message type (defaults to 'info');
	 * @param string $id [optional] An ID for the message to aid later retrieval.
	 */
	
	public static function set($message, $type = self::STATUS_INFO, $namespace = 'default') {
		$messages = Session::get("StatusMessages.$namespace");
		$messages[] = array('Message'=>$message, 'Type'=>$type);
		 Session::set("StatusMessages.$namespace", $messages);
	}
	
	public static function clear($namespace = 'default') {
		Session::clear("StatusMessages.$namespace");
	}
	
}