<?php

use SheDied\SheDieDConfig;
use SheDied\helpers\Lima;

function bot_jogja2022_sweeper()
{
    $count = 0;
    $max = 1;
    $start = 25;
    $end = 33;
    $current = (int) get_transient('jogja2022_next_sweep');
    $mapping = array_reverse(jogja2022_mapping(), TRUE);

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
        $transient = "tsnt_jogja2022_{$source}";

        shedied_exec_bot(new Lima(), [], 1, $transient, true, 'publish');

        next($mapping);
        set_transient('jogja2022_next_sweep', key($mapping));

        if ($count >= $max)
            break;
    }
}

add_action('bot_jogja2022_sweeper', 'bot_jogja2022_sweeper');

/**
 * [
 *    [$source => category]
 * ]
 * @return array
 */
function jogja2022_mapping()
{
    return [
        25 => 6,
        26 => 6,
        27 => 6,
        28 => 7,
        29 => 7,
        30 => 7,
        31 => 7,
        32 => 7,
        33 => 8,
    ];
}

function bot_jogja2022_run()
{
    $count = 0;
    $max = 1;
    $start = 25;
    $end = 33;
    $current = (int) get_transient('jogja2022_next_run');
    $mapping = array_reverse(jogja2022_mapping(), TRUE);

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
        $transient = "tsnt_jogja2022_{$source}";
        $frArrayName = "source_{$source}";
        $sources = SheDieDConfig::pick_Sources([$source], [$category]);

        //$fr = first_Run($frArrayName);
        $helper = new Lima();
        //$helper->yesFirstRun($fr);

        shedied_exec_bot($helper, $sources, 20, $transient, false);
        //update_first_Run($frArrayName, $helper->arrFirstRun());

        next($mapping);
        set_transient('jogja2022_next_run', key($mapping));

        if ($count >= $max)
            break;
    }
}

add_action('bot_jogja2022_run', 'bot_jogja2022_run');
