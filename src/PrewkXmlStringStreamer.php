<?php

namespace Jaapio;


use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Stream;
use Prewk\XmlStringStreamer\Parser;


final class PrewkXmlStringStreamer
{
    const FLUSH_EVERY = 100;

    public function splitFile($path, $targetDir)
    {
        // Prepare our stream to be read with a 1kb buffer
        $stream = new Stream\File($path, 1024);

        $options = array(
            "captureDepth" => 5
        );

        $parser = new Parser\StringWalker($options);

        $streamer = new XmlStringStreamer($parser, $stream);

        $xml = '';
        $count = 0;
        while ($node = $streamer->getNode()) {
            if ($count % self::FLUSH_EVERY === 0) {
                $xml = '<?xml version="1.0" encoding="UTF-8"?><measureSites>';
            }

            $count++;
            $xml .= $node;

            if ($count % static::FLUSH_EVERY === 0) {
                $xml .= '</measureSites>';

                $this->createFile($xml, $targetDir);
            }
        }

        if ($count % static::FLUSH_EVERY !== 0) {
            $xml .= '</measureSites>';

            $this->createFile($xml, $targetDir);
        }
    }

    private function createFile($xml, $targetDir)
    {
        $targetPath = tempnam($targetDir, 'streamer');
        file_put_contents($targetPath, $xml);
    }
}
