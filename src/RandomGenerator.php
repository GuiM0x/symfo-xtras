<?php

/*
 * (c) Guillaume Manier <guillaume.manier.pro@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guiman\SymfoXtras;

class RandomGenerator
{
    /**
     * Return a random number between min and max.
     * Don't use it if you need strong random, use impartialRand function instead.
     *
     * @param int $min - Value min that can be return
     * @param int $max - Value max that can be return
     * @return int
     */
    public function pseudoRand(int $min, int $max): int
    {
        mt_srand($this->generateSeed());
        return mt_rand($min, $max);
    }

    /**
     * Return a strong random number between min and max.
     *
     * @param int $min - Value min that can be return
     * @param int $max - Value max that can be return
     * @return int
     */
    public function impartialRand(int $min, int $max): int
    {
        return random_int($min, $max);
    }

    /**
     * Returns a strong random sequence of characters.
     *
     * @param int $length - Represents the length of the returned key
     * @return string
     */
    public function generateKey(int $length = 32): string
    {
        $key = "";
        $cstrong = null;
        
        while(!$cstrong){
            $bytes = openssl_random_pseudo_bytes($length/2, $cstrong);
            $key = bin2hex($bytes);
        }

        return $key;
    }

    /**
     * Generates a seed based on time.
     * The seed is used with mt_rand generator.
     *
     * @return float
     */
    private function generateSeed(): float
    {
        list($usec, $sec) = explode(' ', microtime());
        $usec *= 1000000;
        return ($usec + $sec);
    }
}