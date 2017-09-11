<?php

namespace Jaapio;

use RuntimeException;


final class XmlReaderDirectTofile
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

        $count = 0;

        while ($reader->name === 'siteMeasurements') {
            if ($count % self::FLUSH_EVERY === 0) {
                $targetPath = tempnam($targetDir, 'ndw');
                file_put_contents($targetPath, '<?xml version="1.0" encoding="UTF-8"?><measureSites>', FILE_APPEND);
            }

            $count++;
            file_put_contents($targetPath, $reader->readOuterXml(), FILE_APPEND);

            if ($count % static::FLUSH_EVERY === 0) {
                file_put_contents($targetPath, '</measureSites>', FILE_APPEND);
            }

            $reader->next('siteMeasurements');
        }

        if ($count % static::FLUSH_EVERY !== 0) {
            file_put_contents($targetPath, '</measureSites>', FILE_APPEND);
        }
    }
}
