<?php 
require __DIR__ . '/../vendor/autoload.php';
use Bowling\Game;

function Test1(){ // Standard game.
    $game = new Game();
    $game->roll(1);
    $game->roll(4);
    $game->roll(4);
    $game->roll(5);
    $game->roll(6);
    $game->roll(4);
    $game->roll(5);
    $game->roll(5);
    $game->roll(10);
    $game->roll(0);
    $game->roll(1);
    $game->roll(7);
    $game->roll(3);
    $game->roll(6);
    $game->roll(4);
    $game->roll(10);
    $game->roll(2);
    $game->roll(8);
    $game->roll(6);
    $game->getScoreRounds();
    fprintf(STDOUT, "TEST 1 PASS:%b \n", $game->getScore()==133);
}

function Test2(){ // Gutter game (all 0s).
    $game = new Game();
    while(!$game->isCompleted()){
        $game->roll(0);
    }
    $game->getScoreRounds();
    fprintf(STDOUT, "TEST 2 PASS:%b \n", $game->getScore()==0);
}

function Test3(){ // Perfect game (all strikes).
    $game = new Game();
    while(!$game->isCompleted()){
        $game->roll(10);
    }
    $game->getScoreRounds();
    fprintf(STDOUT, "TEST 3 PASS:%b \n", $game->getScore()==300);
}

function Test4(){ // Constant rolls.
    $game = new Game();
    while(!$game->isCompleted()){
        $game->roll(4);
    }
    $game->getScoreRounds();
    fprintf(STDOUT, "TEST 4 PASS:%b \n", $game->getScore()==80);
}

function Test5(){ // Spare game.
    $game = new Game();
    while(!$game->isCompleted()){
        $game->roll(5);
    }
    $game->getScoreRounds();
    fprintf(STDOUT, "TEST 5 PASS:%b \n", $game->getScore()==150);
}

function Test6(){ // Near-perfect game (9 pins per roll).
    $game = new Game();
    while(!$game->isCompleted()){
        $game->roll(9);
    }
    $game->getScoreRounds();
    fprintf(STDOUT, "TEST 6 PASS:%b \n", $game->getScore()==190);
}

//#############################################################################################################################################
// RUN

Test1();
Test2();
Test3();
Test4();
Test5();
Test6();