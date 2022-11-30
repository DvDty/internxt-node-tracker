<?php

namespace App\Enums;

enum MetricType: string
{
    case ReputationGained = 'reputation_gained';
    case ReputationLost = 'reputation_lost';
}
