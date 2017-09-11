<?php

namespace Jaapio;

use RuntimeException;


final class XmlReader
{
    const FLUSH_EVERY = 100;

    public function splitFile($path, $targetDir)
    {
        $reader = new \XMLReader();
        if (!$reader->open($path)) {
            throw new RuntimeException(sprintf('Failed to open "%s".', $path));
        }
        // Skip the root element
        while ($reader->read() && $reader->name !== 'siteMeasurements');

        $xml = '';
        $count = 0;

        while ($reader->name === 'siteMeasurements') {
            if ($count % self::FLUSH_EVERY === 0) {
                $xml = '<?xml version="1.0" encoding="UTF-8"?><measureSites>';
            }

            $count++;
            $xml .= $reader->readOuterXML();

            if ($count % static::FLUSH_EVERY === 0) {
                $xml .= '</measureSites>';

                $this->createFile($xml, $targetDir);
            }

            $reader->next('siteMeasurements');
        }

        if ($count % static::FLUSH_EVERY !== 0) {
            $xml .= '</measureSites>';

            $this->createFile($xml, $targetDir);
        }
    }

    private function createFile($xml, $targetDir)
    {
        $targetPath = tempnam($targetDir, 'ndw');
        file_put_contents($targetPath, $xml);
    }
}
