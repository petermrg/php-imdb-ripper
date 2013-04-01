<?php

require_once('imdb-ripper.php');

$imdbRipper = new IMDBRipper();

// Get info from The Matrix
print_r($imdbRipper->main(133093));

