<?php
include_once('OperationBase.php');
class Operation_Seed extends OperationBase{
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
			"Do something related to your cutie mark",
			"Go swimming in the lake in Rural",
			"Go admire the fountain in Central",
			"Go throw rocks off the dam in Industrial",
			"Go for a snack at the Sunset Meadows cafe",
			"Fly a kite!",
			"Visit a friend's house",
			"Go to the market to buy some food",
			"Go shopping for that new toy you've been eyeing",
			"Work in your garden",
			"Visit a relative",
			"Visit a landmark",
			"Go see a movie",
			"Visit one of the Mane 6",
			"Build a treehouse",
			"Make fun of ponies in the street",
			"Formation flying",
			"Hide and Seek",
			"Romp in the wildflowers",
			"Spring cleaning!",
			"Get something sweet from the Sugarcube Corner",
			"Go bowling",
			"Build a soap box racer.  Start a league.",
			"Tag!",
			"Home improvement",
			"Home repairs",
			"Work at your job",
			"Start a business",
			"Karaoke night!",
			"Impromptu public performance",
			"Put on your DJ hat and host a dance",
			"Give someone you know a gift",
			"Give someone you [i]don't[/i] know a gift",
			"Play a prank on someone",
			"Play with an animal or pet",
			"Practice a talent unrelated to your cutie mark",
			"Build a sculpture, topiary, or pottery",
			"Race a friend",
			"Ride the train",
			"Visit Appleoosa",
			"Visit the Crystal Kingdom",
			"Arts and crafts project",
			"Cook for a friend",
			"Bake with a friend",
			"Dive from a waterfall",
			"Visit the park. Play on the swings/slide",
			"Play frisbee with a friend",
			"Invent a mystery for a friend to solve",
			"Decorate your home",
			"Try on a new outfit.  Stroll around town showing it off.",
			"Try out a new hat",
			"Investigate a lovely smell",
			"Investigate a stinky smell",
			"Play in a mud puddle",
			"Yodel",
			"Learn a new instrument",
			"Read a new book",
			"Read your favorite book",
			"Ask for a weather change",
			"Write a book",
			"Write a play",
			"Act in a play",
			"Go visit a town you've never been to before with a friend",
			"Try a new food",
			"Feed animals",
			"Get ice cream with a friend",
			"Get a cold drink with a friend",
			"Get a hot drink with a friend",
			"Memorize words of wisdom",
			"Practice a martial art",
			"Do something you've never done before",
			"Get caught doing something embarassing",
			"Go for a boat ride",
			"Candlelight dinner",
			"Go for a walk with a friend",
			"Set something on fire",
			"Plant some seeds",
			"Accidentally break something you prize",
			"Accidentally break something somebody else prizes",
			"Wagon ride",
			"Pillow fight",
			"Sleepover party",
			"Mare's night out",
			"Stallion's night out",
			"Build a flying machine",
			"Find out a friend's biggest trouble.  Help them resolve it."
		);
		shuffle($common);

		return "{{ " . array_shift($common) . " }}";
	}
}