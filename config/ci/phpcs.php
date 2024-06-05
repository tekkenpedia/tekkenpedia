<?php

declare(strict_types=1);

use steevanb\PhpCodeSniffs\Steevanb\Sniffs\Uses\GroupUsesSniff;

GroupUsesSniff::addFirstLevelPrefix('App');

GroupUsesSniff::addSymfonyPrefixes();
GroupUsesSniff::addThirdLevelPrefix('Symfony\\Bridge');
