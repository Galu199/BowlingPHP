<?php namespace Bowling;

class FrameLast extends Frame{
    protected const maxPins = 10;
    protected const maxRolls = 3;

    public function roll(int $knockedPins): void {
        if(!$this->isCompleted())
        {
            $knockedPins = max([$knockedPins,0]);
            if(count($this->rolls)==1 && $this->rolls[0] < 10){
                $knockedPins = min([$knockedPins,self::maxPins-$this->rolls[0]]);
            }
            if(count($this->rolls)==1 && $this->rolls[0] == 10){
                $knockedPins = min([$knockedPins,self::maxPins]);
            }
            if(count($this->rolls)==2 ){
                $knockedPins = min([$knockedPins,self::maxPins]);
            }
            $this->rolls[] = $knockedPins;
            return;
        }
        throw new \Exception("Frame is completed. You can't add more knocked pins.");
    }

    public function isCompleted(): bool{
        if(count($this->rolls) >= self::maxRolls) return true;
        if (count($this->rolls) >= 2 && (!$this->isStrike() && !$this->isSpare()) ) return true;
        return false;
    }

    public function getBonus(): int{
        return 0;
    }
}
