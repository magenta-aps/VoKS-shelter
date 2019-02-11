<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Libraries\Cache;

use Illuminate\Cache\FileStore;

/**
 * Class SecondsFileStore
 *
 * @package  BComeSafe\Libraries\Cache
 */
class SecondsFileStore extends FileStore
{

    /**
     * @param int $minutes
     * @return int
     */
    protected function expiration($minutes)
    {
        if ($minutes === 0) {
            return 9999999999;
        }
        return time() + $minutes;
    }

    /**
     * @param string $key
     * @return array
     */
    protected function getPayload($key)
    {
        $path = $this->path($key);

        // If the file doesn't exists, we obviously can't return the cache so we will
        // just return null. Otherwise, we'll get the contents of the file and get
        // the expiration UNIX timestamps from the start of the file's contents.
        try {
            $expire = substr($contents = $this->files->get($path), 0, 10);
        } catch (\Exception $e) {
            return array('data' => null, 'time' => null);
        }

        // If the current time is greater than expiration timestamps we will delete
        // the file and return null. This helps clean up the old files and keeps
        // this directory much cleaner for us as old files aren't hanging out.
        if (time() >= $expire) {
            $this->forget($key);

            return array('data' => null, 'time' => null);
        }

        $data = unserialize(substr($contents, 10));

        // Next, we'll extract the number of minutes that are remaining for a cache
        // so that we can properly retain the time for things like the increment
        // operation that may be performed on the cache. We'll round this out.
        $time = ($expire - time());

        return compact('data', 'time');
    }
}
