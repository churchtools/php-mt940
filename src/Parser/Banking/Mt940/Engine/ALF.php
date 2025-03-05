<?php

namespace Kingsquare\Parser\Banking\Mt940\Engine;

use Kingsquare\Parser\Banking\Mt940\Engine;

/**
 * @author Timotheus Pokorra (timotheus.pokorra@solidcharity.com)
 * @license http://opensource.org/licenses/MIT MIT
 *
 * This is for german banks, for example Sparkasse
 */
class ALF extends Engine
{
    /**
     * returns the name of the bank.
     *
     * @return string
     */
    protected function parseStatementBank()
    {
        return 'ALF';
    }

    /**
     * Overloaded: ALF uses 60M and 60F.
     *
     * @inheritdoc
     */
    protected function parseStatementStartPrice()
    {
        return $this->parseStatementPrice('60[FM]');
    }

    /**
     * Overloaded: ALF uses 60M and 60F.
     *
     * @inheritdoc
     */
    protected function parseStatementStartTimestamp()
    {
        return $this->parseTimestampFromStatement('60[FM]');
    }

    /**
     * Overloaded: ALF uses 60M and 60F.
     *
     * @inheritdoc
     */
    protected function parseStatementEndTimestamp()
    {
        return $this->parseTimestampFromStatement('60[FM]');
    }

    /**
     * Overloaded: ALF uses 62M and 62F.
     *
     * @inheritdoc
     */
    protected function parseStatementEndPrice()
    {
        return $this->parseStatementPrice('62[FM]');
    }

    /**
     * Overloaded: ALF does not have a header line.
     *
     * @inheritdoc
     */
    protected function parseStatementData()
    {
        return preg_split(
            '/(^:20:|^-X{,3}[\s]+|\Z)/m',
            $this->getRawData(),
            -1,
            PREG_SPLIT_NO_EMPTY
        );
    }

    /**
     * Overloaded: Is applicable if first or second line has :20:STARTUMS or first line has -.
     *
     * @inheritdoc
     */
    public static function isApplicable($string)
    {
        $firstline = strtok($string, "\r\n\t");
        $secondline = strtok("\r\n\t");

        $foundA = preg_match('/^:20:.*\((\d+)\)$/', $firstline, $matchesA);
        $foundB = preg_match('/^:25:\d+\/(\d+)$/', $secondline, $matchesB);

        return $foundA && $foundB && $matchesA[1] === $matchesB[1];
    }
}
