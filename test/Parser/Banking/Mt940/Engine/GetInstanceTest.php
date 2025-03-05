<?php

namespace Kingsquare\Parser\Banking\Mt940;

use Kingsquare\Parser\Banking\Mt940\Engine\Unknown;
use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\Error\Notice;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class GetInstanceTest extends TestCase
{
    /**
     *
     */
    public function testUnknownEngineRaisesANotice()
    {
        try {
            Engine::__getInstance('this is an unknown format :)');
        } catch (\Exception $exptected) {
            $this->assertInstanceOf(Notice::class, $exptected);
            return  ;
        }
        $this->fail('Did not receive the notice');
    }

    /**
     * @dataProvider enginesProvider
     *
     * @param string $engineString
     * @param string $source
     */
    public function testEngine($engineString, $source)
    {
        $engine = @Engine::__getInstance($source);
        $this->assertInstanceOf('\\Kingsquare\\Parser\\Banking\\Mt940\\Engine\\'.$engineString, $engine);
    }

    /**
     * @dataProvider enginesProvider
     *
     * @param string $engineString
     * @param string $source
     */
    public function testSingleEngine($engineString, $source)
    {
        Engine::resetEngines();
        $engine = @Engine::__getInstance($source);
        $this->assertInstanceOf(Unknown::class, $engine);
    }

    /**
     * @return array
     */
    public function enginesProvider()
    {
        return [
                ['Abn', file_get_contents(__DIR__.'/Abn/sample')],
                ['Ing', file_get_contents(__DIR__.'/Ing/sample')],
                ['Rabo', file_get_contents(__DIR__.'/Rabo/sample')],
                ['Spk', file_get_contents(__DIR__.'/Spk/sample')],
                ['ALF', file_get_contents(__DIR__.'/ALF/sample')],
                ['Triodos', file_get_contents(__DIR__.'/Triodos/sample')],
                ['Unknown', 'this is an unknown format :)'],
        ];
    }
}
