<?php

namespace Parser\Banking\Mt940\Engine\ALF;

use Kingsquare\Parser\Banking\Mt940\Engine\Abn;
use Kingsquare\Parser\Banking\Mt940\Engine\ALF;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ParseTest extends TestCase
{
    /**
     * @var ALF
     */
    private $engine;

    protected function setUp(): void
    {
        $this->engine = new ALF();
        $this->engine->loadString(file_get_contents(__DIR__.'/sample'));
    }

    public function testParseStatementBank()
    {
        $method = new \ReflectionMethod($this->engine, 'parseStatementBank');
        $method->setAccessible(true);
        $this->assertEquals('ALF', $method->invoke($this->engine));
    }

    public function testParsesAllFoundStatements()
    {
        $statements = $this->engine->parse();

        $this->assertCount(1, $statements);
        $statement = $statements[0];

        $this->assertEquals('08-01-2025', $statement->getStartTimestamp('d-m-Y'));
        $this->assertEquals('08-01-2025', $statement->getEndTimestamp('d-m-Y'));
        $this->assertEquals(-652.21, $statement->getDeltaPrice());

    }

    public function testParseTransactionDebitCredit()
    {
        $statements = $this->engine->parse();
        $transactions = reset($statements)->getTransactions();
        $firstTransaction = reset($transactions);

        $this->assertEquals('C', $firstTransaction->getDebitCredit());
    }

    public function testParseTransactionPrice()
    {
        $statements = $this->engine->parse();
        $transactions = reset($statements)->getTransactions();
        $firstTransaction = reset($transactions);

        $this->assertEquals(652.21, $firstTransaction->getPrice());
    }
}
