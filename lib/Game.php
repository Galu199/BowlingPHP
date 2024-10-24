<?php namespace Bowling;


/**
 * Represents a bowling game where players roll balls to knock down pins.
 * This class handles the logic for scoring and managing frames and rolls.
 * 
 * @package Bowling
 */
class Game{
    /**
     * The maximum number of rounds/frames in a game.
     *
     * @var int
     */
    private const maxFrames = 10;

    /**
     * The head frame (first frame) in the game.
     *
     * @var Frame
     */
    private Frame $frameHead;

    /**
     * The Tail frame (last frame) in the game.
     *
     * @var Frame
     */
    private Frame $frameTail;

    /**
     * The current frame being played.
     *
     * @var Frame
     */
    private Frame $frameCurrent;

    public function __construct() {
        $this->frameHead = new Frame(1);
        $this->frameCurrent = $this->frameHead;
        for($i = 2; $i <= self::maxFrames - 1; $i++){
            $newFrame = new Frame($i);
            $this->frameCurrent->addFrame($newFrame);
            $this->frameCurrent = $this->frameCurrent->getNextFrame();
        }
        $this->frameTail = new FrameLast(10);
        $this->frameCurrent->addFrame($this->frameTail);
        $this->frameCurrent = $this->frameHead;
    }

    /**
     * Calculates and returns the total score of the game.
     *
     * @return int The total score of the game.
     */
    public function getScore(): int {
        $score = 0;
        $currentFrame = $this->frameHead;
        while ($currentFrame != null) {
            $score += $currentFrame->getScore();
            $currentFrame = $currentFrame->getNextFrame();
        }
        return $score;
    }

    /**
     * Rolls the ball and records the number of pins knocked down.
     *
     * @param int $pins The number of pins knocked down in this roll.
     * @return void
     */
    public function roll(int $pins): void {
        $this->frameCurrent->roll($pins);
        fprintf(STDOUT, "Round: %d/%d, Roll: %d, Points: %d\n", $this->frameCurrent->getIndex(), self::maxFrames, $pins, $this->getScore());
        if ( $this->frameCurrent->isCompleted() && $this->frameCurrent->getNextFrame() ){
            $this->frameCurrent = $this->frameCurrent->getNextFrame();
        }
    }

    /**
     * gets the total score for each round in the game.
     * @return array
     */
    public function getScoreRounds(): array {
        $cumulativeScores = array(0);
        $currentFrame = $this->frameHead;
        while($currentFrame != null){
            $score = $currentFrame->getScore() + end($cumulativeScores);
            $cumulativeScores[] = $score;
            $currentFrame = $currentFrame->getNextFrame();
        }
        array_shift($cumulativeScores);
        return $cumulativeScores;
    }

    /**
     * Check if game is finished.
     * 
     * @return bool true or false
     */
    public function isCompleted(): bool {
        return $this->frameCurrent === $this->frameTail && $this->frameCurrent->isCompleted();
    }
}