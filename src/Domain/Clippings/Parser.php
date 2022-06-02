<?php

namespace App\Domain\Clippings;

final class Parser
{
    /** @return array<string, Clipping[]> */
    public static function fromPath(string $path): array
    {
        $clips = explode('==========', file_get_contents($path));
        $clippings = [];
        $note = null;
        foreach ($clips as $string) {
            $clip = Clipping::fromString($string, $note);
            if ($clip === null) {
                continue;
            }
            $note = $clip->type === Type::NOTE ? $clip : null;
            if ($note === null) {
                $clippings[$clip->book][] = $clip;
            }
        }

        return $clippings;
    }
}
