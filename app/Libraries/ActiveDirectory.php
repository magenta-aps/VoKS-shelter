<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Libraries;

use Adldap\Adldap;

class ActiveDirectory
{
    public static function getGroupMembers($group = 'CrisisTeam')
    {
        try {
            $ad = new Adldap(config('ad.connection'));
            $res = $ad->groups()
                ->search()
                ->whereContains('cn', $group)
                ->first();

            if(!empty($res)) {
                return $res->getMembers();
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }
}