<?php

class Tag {

	// Properties
	private $tag;
	private $tagId;
	private $amount;
	private $startSlot1;
	private $endSlot1;
	private $startSlot2;
	private $endSlot2;

	
	// Methods
	public function __construct($tag, $tagId, $amount, $startSlot1, $endSlot1, $startSlot2, $endSlot2) {
		$this->tag = $tag;
		$this->tagId = $tagId;
		$this->amount = $amount;
		$this->startSlot1 = $startSlot1;
		$this->endSlot1 = $endSlot1;
		$this->startSlot2 = $startSlot2;
		$this->endSlot2 = $endSlot2;
	}
	
	public function getArray() {
		return array("tag" => $this->tag, "tid" => $this->tagId, "a" => $this->amount, "s1" => $this->startSlot1, "s1e" => $this->endSlot1, "s2" => $this->startSlot2, "s2e" => $this->endSlot2);
	}
}

?>
