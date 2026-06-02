<?php

namespace App\Services;

class ProfanityService
{
    /**
     * List of prohibited words (Indonesian and English common profanities).
     * This is a simplified list for demonstration.
     */
    protected array $badWords = [
        // Indonesian (Common insults)
        'anjing', 'babi', 'monyet', 'bangsat', 'brengsek', 'tolol', 'goblok', 'idiot', 'kontol', 'memek', 'ngentot', 'asu',
        'bajingan', 'perek', 'jablay', 'lonte', 'pecun', 'jembut', 'peler', 'itil', 'bego', 'bencong', 'bencong',
        // English
        'fuck', 'shit', 'asshole', 'bitch', 'bastard', 'cunt', 'dick', 'pussy', 'faggot', 'nigger',
    ];

    /**
     * Checks if a text contains any profanity.
     */
    public function hasProfanity(string $text): bool
    {
        $text = strtolower($text);

        foreach ($this->badWords as $word) {
            // Using regex to match exact words or words within words
            if (preg_match("/\b$word\b/i", $text) || str_contains($text, $word)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Mask detected profanity with asterisks.
     */
    public function mask(string $text): string
    {
        foreach ($this->badWords as $word) {
            $replacement = str_repeat('*', strlen($word));
            $text = preg_replace("/\b$word\b/i", $replacement, $text);
        }

        return $text;
    }
}
