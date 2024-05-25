<?php

namespace App\Domain\Clippings;

use DateTimeImmutable;

final class Clipping
{
    public readonly string $id;
    public readonly string $bookId;
    public readonly string $source;

    public function __construct(
        public readonly Type $type,
        public readonly ?string $location,
        public readonly ?string $page,
        public readonly string $book,
        public readonly string $author,
        public readonly string $text,
        public readonly DateTimeImmutable $createdOn,
        public readonly ?self $note
    ) {
        $this->id = 'c' . hash('xxh128', serialize($this));
        $this->bookId = 'b' . hash('xxh128', $this->book . ' - ' . $this->author);
        $this->source = $this->buildSource();
    }

    public static function fromString(string $clipping, ?self $note): ?self
    {
        $clipping = trim($clipping);
        $parts = [];
        if (!preg_match(
            '/(\w.*?) \((.*?)\)\s+- Your (\S+?) (?:on page (\S+?) \||at)(?: location (\S+?) \|)? Added on \w+, (.+?:.+?)\s+(.*)/',
            $clipping,
            $parts
        )) {
            return null;
        }

        return new self(
            Type::from($parts[3]),
            $parts[5] === '' ? null : trim($parts[5]),
            $parts[4] === '' ? null : trim($parts[4]),
            trim($parts[1]),
            trim(str_replace(';', '', $parts[2])),
            trim($parts[7]),
            DateTimeImmutable::createFromFormat('j F Y H:i:s', $parts[6]),
            $note
        );
    }

    public function buildSource(): string
    {
        $source = [
            $this->author,
            '. ',
            $this->book,
            ' (',
        ];


        if ($this->page !== null) {
            $source[] = 'page ' . $this->page;
            if ($this->location !== null) {
                $source[] = ', ';
            }
        }

        if ($this->location !== null) {
            $source[] = 'location ' . $this->location;
        }

        $source[] = ') Kindle Edition';

        return implode($source);
    }
}
