<?php

namespace Jaapio\Benchmarks;

use Jaapio\PrewkXmlStringStreamer;
use Jaapio\XmlReader;
use Jaapio\XmlReaderDirectTofile;
use Jaapio\XmlReaderInner;

/**
 * @AfterClassMethods({"cleanUp"})
 */
class XmlSplitBench
{

    /**
     * @ Revs({1,2})
     * @ Iterations(5)
     */
    public function benchXmlReaderOuter()
    {
        $consumer = new XmlReader();
        $consumer->splitFile(realpath(__DIR__ . '/../source/trafficspeed.xml'), realpath(__DIR__ . '/../target'));
    }

    public function benchXmlReaderInner()
    {
        $consumer = new XmlReaderInner();
        $consumer->splitFile(realpath(__DIR__ . '/../source/trafficspeed.xml'), realpath(__DIR__ . '/../target'));
    }

    public function benchXmlReaderDirectToFile()
    {
        $consumer = new XmlReaderDirectTofile();
        $consumer->splitFile(realpath(__DIR__ . '/../source/trafficspeed.xml'), realpath(__DIR__ . '/../target'));
    }

    public function benchPrewkXmlStringStreamer()
    {
        $consumer = new PrewkXmlStringStreamer();
        $consumer->splitFile(realpath(__DIR__ . '/../source/trafficspeed.xml'), realpath(__DIR__ . '/../target'));
    }

    public static function cleanUp()
    {
        system(sprintf('rm %s/*', realpath(__DIR__ . '/../target')));
    }
}
