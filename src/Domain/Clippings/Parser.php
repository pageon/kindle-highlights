<?php

namespace App\Domain\Clippings;

final class Parser
{
    /** @return array<string, Clipping[]> */
    public static function fromPath(string $path): array
    {
        $clips = explode('==========', file_get_contents($path));
        $clippings = [];
        $highlight = null;
        foreach ($clips as $string) {
            $clip = Clipping::fromString($string);
            if ($clip === null) {
                continue;
            }
            $note = $clip->type === Type::NOTE ? $clip : null;
            if ($note instanceof Clipping) {
                $highlight->note = $note;
            } else {
                $clippings[$clip->bookId][] = $clip;
                $highlight = $clip;
            }
        }

        return $clippings;
    }
}
