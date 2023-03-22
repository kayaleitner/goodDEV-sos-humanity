<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('templates/single-people.twig', $context);
