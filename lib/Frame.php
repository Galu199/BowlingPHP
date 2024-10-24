<?php namespace Bowling;

/**
 * Represents a Frame in game where player's rolls are recoreded.
 * 
 * This class handles the logic for specyfic round.
 * 
 * @package Bowling
 */
class Frame{
    /**
     * index of current frame.
     * only used for naming. 
     *
     * @var int
     */
    protected int $index;

    /**
     * The maximum number of chances to knock down pins.
     *
     * @var int
     */
    protected const maxRolls = 2;

    /**
     * The maximum number of pins.
     *
     * @var int
     */
    protected const maxPins = 10;

    /**
     * The next frame in the sequence.
     * 
     * This points to the subsequent frame in the game. If this is the last frame, it will be null.
     *
     * @var Frame|null
     */
    protected ?Frame $next = null;

    /**
     * The previous frame in the sequence.
     * 
     * This points to the frame that occurred before the current frame. If this is the first frame, it will be null.
     *
     * @var Frame|null
     */
    protected ?Frame $prev = null;

    /**
     * The rolls recorded in the current frame.
     * 
     * This array contains the number of pins knocked down in each roll for this frame.
     *
     * @var int[]
     */
    protected array $rolls = array();

    /**
     * Constructs a new game with a specified number of rounds and pins.
     *
     * @param int $index The name of frame.
     */
    public function __construct(int $index) {
        $this->index = $index;
    }

    /**
     * Getter for Index.
     * 
     * @return int
     */
    public function getIndex(): int {
        return $this->index;
    }

    /**
     * method for adding Frame as next frame if possible.
     * 
     * @param Frame $frame
     * @return void
     * @throws \Exception it can't add next Frame because it already has next Frame.
     */
    public function addFrame(Frame $frame): void{
        if($this->next != null) throw new \Exception("This Frame has next Frame.");
        $this->next = $frame;
        $frame->prev = $this;
    }

    /**
     * Getter for next frame.
     * 
     * @return Frame|null
     */
    public function getNextFrame(): ?Frame{
        return $this->next;
    }

    /**
     * Getter for previous frame.
     * 
     * @return Frame|null
     */
    public function getPrevFrame(): ?Frame{
        return $this->prev;
    }

    /**
     * Handilng adding new rolled pins
     * if full then add this roll to next frame.
     * if there is no next frame allow to overflow frame.
     * 
     * @param int $knockedPins
     * @return void
     * @throws \Exception Frame is completed. You can't add more knocked pins.
     */
    public function roll(int $knockedPins): void {
        if($this->isCompleted()) throw new \Exception("Frame is completed. You can't add more knocked pins.");
        $knockedPins = max([$knockedPins,0]);
        $knockedPins = min([$knockedPins, self::maxPins-$this->getKnockedPins()]);
        $this->rolls[] = $knockedPins;
        return;
    }

    /**
     * Get the sum of all knoced pins in rolls list.
     * @return int sum of rolls
     */
    public function getKnockedPins(): int{
        $sum = 0;
        foreach($this->rolls as $pins){
            $sum += $pins;
        }
        return $sum;
    }

    /**
     * Summary of getScore
     * @return int
     */
    public function getScore(): int{
        $sum = 0;
        $sum += $this->getKnockedPins() + $this->getBonus();
        return $sum;
    }

    public function getBonus(): int{
        if ( $this->isStrike() ) {
            return $this->getNextFrameTwoRolls($this);
        } elseif ( $this->isSpare() ) {
            return $this->getNextFrameOneRoll($this);
        }
        return 0;
    }

    /**
     * check if frame is full.
     * @return bool
     */
    public function isCompleted(): bool{
        return $this->getKnockedPins() >= self::maxPins || count($this->rolls) >= self::maxRolls;
    }

    /**
     * check if this frame has spare.
     * @return bool
     */
    public function isSpare(): bool{
        if(count($this->rolls) >= 2) return $this->rolls[0] + $this->rolls[1] == self::maxPins;
        return false;
    }

    /**
     * check if this frame has strike.
     * @return bool
     */
    public function isStrike(): bool{
        if(count($this->rolls) >= 1) return $this->rolls[0] == self::maxPins;
        return false;
    }

    protected function getNextFrameOneRoll(Frame $frame): int {
        if($frame->next == null) return 0;
        if ( count($frame->next->rolls) > 0)
            return $frame->next->rolls[0];
        return 0;
    }

    protected function getNextFrameTwoRolls(Frame $frame): int {
        if($frame->next == null) return 0;
        $sum = 0;
        $rolls = $frame->next->rolls;
        if(count($rolls) == 0) return 0; 
        if(count($rolls) == 1)
        {
            $sum = $rolls[0] + $this->getNextFrameOneRoll($frame->next);
        }
        elseif(count($rolls) > 1)
        {
            $sum += $frame->next->rolls[0] + $frame->next->rolls[1];
        }
        return $sum;
    } 
}
