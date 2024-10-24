<?php
require __DIR__ . '/../vendor/autoload.php';
use Bowling\Game;

/**
 * Function to start game and handle inputs
 * @return void
 */
function UserInputBowling(){
    fprintf(STDOUT, "Welcome to Bowling Scoring\n");
    
    $game = new Game();
    
    while (!$game->isCompleted()) {
        fprintf(STDOUT, "Knocked down pins (0-10): ");
        
        $input = trim(fgets(STDIN));
        
        // Check if the input is numeric
        if (!is_numeric($input)) {
            fprintf(STDOUT, "Invalid input! Please enter a number between 0 and 10.\n");
            continue;
        }
        
        $pins = (int)(float)$input; // Convert input to an integer

        // Validate the number of pins knocked down
        if ($pins < 0 || $pins > 10) {
            fprintf(STDOUT, "Invalid number of pins! Please enter a number between 0 and 10.\n");
            continue;
        }

        try {
            $game->roll($pins);
        } catch (\Exception $e) {
            fprintf(STDOUT, "Error occurred: %s\n", $e->getMessage());
        }
    }

    // Display the cumulative score for all rounds at the end
    foreach( $game->getScoreRounds() as $i => $score ){
        fprintf(STDOUT, "Round: %d/%d, Score: %d\n", $i+1, 10, $score);
    }

    fprintf(STDOUT, "GAME OVER\n");
}

UserInputBowling();