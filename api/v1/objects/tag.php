<?php

class Tag {

	// Properties
	private $tagUpdate;
	private $tagId;
	private $amount;
	private $startSlot1;
	private $endSlot1;
	private $startSlot2;
	private $endSlot2;

	
	// Methods
	public function __construct($tagUpdate, $tagId, $amount, $startSlot1, $endSlot1, $startSlot2, $endSlot2) {
		$this->tagUpdate = $tagUpdate;
		$this->tagId = $tagId;
		$this->amount = $amount;
		$this->startSlot1 = $startSlot1;
		$this->endSlot1 = $endSlot1;
		$this->startSlot2 = $startSlot2;
		$this->endSlot2 = $endSlot2;
	}
	
	public function getArray() {
		return array("tagUpdate" => $this->tagUpdate, "tagID" => $this->tagId, "amount" => $this->amount, "slot1Start" => $this->startSlot1, "slot1End" => $this->endSlot1, "slot2Start" => $this->startSlot2, "slot2End" => $this->endSlot2);
	}
}

?>
