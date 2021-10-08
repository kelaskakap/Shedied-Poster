<?php

use SheDied\SheDieDConfig;
use SheDied\helpers\Songo;

function bot_appzone_sweeper()
{
    $count = 0;
    $max = 1;
    $start = 2;
    $end = 42;
    $current = (int) get_transient('appzone_next_sweep');
    $mapping = array_reverse(appzone_mapping(), TRUE);

    if ($current < $start OR $current > $end)
    //$current = $start; // mapping gak di-reverse
        $current = $end; // mapping di-reverse

    foreach ($mapping as $source => $category)
    {
        if ($current != $source)
        {
            next($mapping);
            continue;
        }

        $count++;
        $transient = "tsnt_appzone_{$source}";

        shedied_exec_bot(new Songo(), [], 1, $transient, true, 'draft');

        next($mapping);
        set_transient('appzone_next_sweep', key($mapping));

        if ($count >= $max)
            break;
    }
}

add_action('bot_appzone_sweeper', 'bot_appzone_sweeper');

/**
 * [
 *    [$source => category]
 * ]
 * @return array
 */
function appzone_mapping()
{
    return [
        2 => 4,
        3 => 5,
        4 => 6,
        5 => 7,
        6 => 8,
        7 => 9,
        8 => 10,
        9 => 11,
        10 => 12,
        11 => 13,
        12 => 14,
        13 => 15,
        14 => 16,
        15 => 17,
        16 => 18,
        17 => 19,
        18 => 20,
        19 => 21,
        20 => 22,
        21 => 25,
        22 => 26,
        23 => 27,
        24 => 28,
        25 => 29,
        26 => 30,
        27 => 31,
        28 => 32,
        29 => 33,
        30 => 34,
        31 => 35,
        32 => 36,
        33 => 37,
        34 => 38,
        35 => 39,
        36 => 40,
        37 => 41,
        38 => 42,
        39 => 43,
        40 => 44,
        41 => 45,
        42 => 46,
    ];
}

function bot_appzone_run()
{
    $count = 0;
    $max = 1;
    $start = 2;
    $end = 42;
    $current = (int) get_transient('appzone_next_run');
    $mapping = array_reverse(appzone_mapping(), TRUE);

    if ($current < $start OR $current > $end)
    //$current = $start; // mapping gak di-reverse
        $current = $end; // mapping di-reverse

    foreach ($mapping as $source => $category)
    {
        if ($current != $source)
        {
            next($mapping);
            continue;
        }

        $count++;
        $transient = "tsnt_appzone_{$source}";
        $frArrayName = "source_{$source}";
        $sources = SheDieDConfig::pick_Sources([$source], [$category]);

        //$fr = first_Run($frArrayName);
        $helper = new Songo();
        //$helper->yesFirstRun($fr);

        shedied_exec_bot($helper, $sources, 20, $transient, false);
        //update_first_Run($frArrayName, $helper->arrFirstRun());

        next($mapping);
        set_transient('appzone_next_run', key($mapping));

        if ($count >= $max)
            break;
    }
}

add_action('bot_appzone_run', 'bot_appzone_run');
