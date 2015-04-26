<?php
include_once('OperationBase.php');
class Operation_M8b extends OperationBase{
	public $operator;
	public $data;
	public static $args = 0;
	public $messages = array();

	function __construct( $args ){
		if(self::$args != count($args)){
			return (implode(" ", $args));
		}

		$this->data = $args;
	}

	public function getValue(){
		try{
			return $this->roll();
		} catch (Exception $e){
			throw $e;
		}
	}

	private function roll(){
		$common = array(
			"It is certain",
			"It is decidedly so",
			"Without a doubt",
			"Yes definitely",
			"You may rely on it",
			"As I see it, yes",
			"Most likely",
			"Outlook good",
			"Yes",
			"Signs point to yes",
			"Reply hazy try again",
			"Ask again later",
			"Better not tell you now",
			"Cannot predict now",
			"Concentrate and ask again",
			"Don't count on it",
			"My reply is no",
			"My sources say no",
			"Outlook not so good",
			"Very doubtful",
			"Firefly did it",
			"Crystal Shard blew it up",
			"Muffin"
		);
		shuffle($common);

		return "{{ The 8-ball swirls and says: \"" . array_shift($common) . "\" }}";
	}
}