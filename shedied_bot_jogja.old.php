<?php

use SheDied\SheDieDConfig;
use SheDied\helpers\Lima;

function bot_olx_jogja_1() {

    //4 jam sekali
    //1 mobil bekas
    //10 indekos
    //13 lowongan
    $sources = SheDieDConfig::pick_Sources([1, 10, 13], [9, 39, 57]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_1', false);
}

add_action('bot_olx_jogja_1', 'bot_olx_jogja_1');

function bot_olx_jogja_2() {

    //4 jam sekali
    //9 tanah
    //8 disewakan rumah
    //12 disewakan banguna komersil

    $sources = SheDieDConfig::pick_Sources([9, 8, 12], [38, 37, 41]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_1', false);
}

add_action('bot_olx_jogja_2', 'bot_olx_jogja_2');

function bot_olx_jogja_3() {

    //12 jam sekali
    //18 handphone
    //19 laptop
    //23 hewan peliharaan

    $sources = SheDieDConfig::pick_Sources([18, 19, 23], [64, 65, 71]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_3', false);
}

add_action('bot_olx_jogja_3', 'bot_olx_jogja_3');

function bot_olx_jogja_4() {

    //12 jam sekali
    //14 jasa
    //7 dijual rumah apartemen
    //11 dijual bangunan komersil

    $sources = SheDieDConfig::pick_Sources([14, 7, 11], [58, 23, 40]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_4', false);
}

add_action('bot_olx_jogja_4', 'bot_olx_jogja_4');

function bot_olx_jogja_5() {

    //48 jam sekali
    //6 truk kendaraan komersil
    //16 perlengkapan usaha
    //21 mebel

    $sources = SheDieDConfig::pick_Sources([6, 16, 21], [36, 61, 67]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_5', false);
}

add_action('bot_olx_jogja_5', 'bot_olx_jogja_5');

function bot_olx_jogja_6() {

    //72 jam sekali
    //2 aksesori mobil
    //3 audio mobil
    //4 sparepart mobil
    $sources = SheDieDConfig::pick_Sources([2, 3, 4], [10, 33, 34]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_6', false);
}

add_action('bot_olx_jogja_6', 'bot_olx_jogja_6');

function bot_olx_jogja_7() {

    //72 jam sekali
    //15 peralatan kantor
    //17 keperluan industri
    //20 perlengkapan rumah
    $sources = SheDieDConfig::pick_Sources([15, 17, 20], [60, 62, 69]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_7', false);
}

add_action('bot_olx_jogja_7', 'bot_olx_jogja_7');

function bot_olx_jogja_8() {

    //72 jam sekali
    //21 mebel
    //22 dekorasi rumah
    //5 velg ban
    $sources = SheDieDConfig::pick_Sources([21, 22, 5], [67, 68, 35]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_8', false);
}

add_action('bot_olx_jogja_8', 'bot_olx_jogja_8');

function bot_olx_jogja_9() {

    //4 jam sekali
    //8 disewakan rumah
    //10 indekos
    $sources = SheDieDConfig::pick_Sources([8, 10], [37, 39]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_9', false);
}

add_action('bot_olx_jogja_9', 'bot_olx_jogja_9');

function bot_olx_jogja_10() {

    //2 hari sekali
    //7 rumah dijual
    //12 disewakan bangunan komersil

    $sources = SheDieDConfig::pick_Sources([7, 12], [23, 41]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_10', false);
}

add_action('bot_olx_jogja_10', 'bot_olx_jogja_10');

function bot_olx_jogja_11() {

    //3 hari sekali
    //14 rental jasa

    $sources = SheDieDConfig::pick_Sources([14], [58]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_11', false);
}

add_action('bot_olx_jogja_11', 'bot_olx_jogja_11');

function bot_olx_jogja_12() {

    //7 hari sekali
    //9 tanah

    $sources = SheDieDConfig::pick_Sources([9], [38]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_12', false);
}

add_action('bot_olx_jogja_12', 'bot_olx_jogja_12');

function bot_olx_jogja_13() {

    //24 jam sekali
    //13 lowongan
    $sources = SheDieDConfig::pick_Sources([13], [57]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_13', false);
}

add_action('bot_olx_jogja_13', 'bot_olx_jogja_13');

function bot_olx_jogja_sweeper() {

    $helper = new Lima();

    /* shedied_exec_bot($helper, [], 1, 'tsnt_olx_jogja_1', true);
      shedied_exec_bot($helper, [], 1, 'tsnt_olx_jogja_2', true);
      shedied_exec_bot($helper, [], 1, 'tsnt_olx_jogja_3', true);
      shedied_exec_bot($helper, [], 1, 'tsnt_olx_jogja_4', true);
      shedied_exec_bot($helper, [], 1, 'tsnt_olx_jogja_5', true);
      shedied_exec_bot($helper, [], 1, 'tsnt_olx_jogja_6', true);
      shedied_exec_bot($helper, [], 1, 'tsnt_olx_jogja_7', true);
      shedied_exec_bot($helper, [], 1, 'tsnt_olx_jogja_8', true); */
    shedied_exec_bot($helper, [], 2, 'tsnt_olx_jogja_9', true);
    shedied_exec_bot($helper, [], 1, 'tsnt_olx_jogja_10', true);
    shedied_exec_bot($helper, [], 1, 'tsnt_olx_jogja_11', true);
    shedied_exec_bot($helper, [], 1, 'tsnt_olx_jogja_12', true);
    shedied_exec_bot($helper, [], 1, 'tsnt_olx_jogja_13', true);
}

add_action('bot_olx_jogja_sweeper', 'bot_olx_jogja_sweeper');
