<?php

namespace Kingsquare\Parser\Banking\Mt940\Engine;

use Kingsquare\Banking\Statement;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ParseTest extends TestCase
{
    /**
     * @var Statement[]
     */
    private $statements;

    protected function setUp(): void
    {
        $engine = new Unknown();
        $engine->loadString(file_get_contents(__DIR__.'/sample'));
        $this->statements = $engine->parse();
    }

    /**
     *
     */
    public function test()
    {
        self::assertEquals('50092100', $this->statements[0]->getBankCode());
        self::assertEquals('1782002', $this->statements[0]->getAccountNumber());
        self::assertEquals('acfbc24843a9b17f3eb8d316ac10694d0787304202254c5d1bee62f537c18048', $this->statements[0]->getTransactions()[0]->getFingerprint());
        self::assertEquals('DE99327719624505558948', $this->statements[0]->getTransactions()[0]->getAccount());
    }

    public function testParsesIbanFromPurposeFieldsWhenAccountFieldIsMissing()
    {
        $engine = new Unknown();
        $engine->loadString(file_get_contents(__DIR__.'/embedded-iban-sample'));

        $statements = $engine->parse();

        self::assertEquals('DE43123456789012345678', $statements[0]->getTransactions()[0]->getAccount());
    }
}
