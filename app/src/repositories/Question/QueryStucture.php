<?php

declare(strict_types=1);

namespace App\repositories\Question;

use App\Nlp\Token\Token;

class QueryStucture
{
    private string $originQueryString;
    private array $tokens;
    /**
     * @param Token[] $suggestions
     */
    private array $suggestions = [];
    public function __construct(string $queryString)
    {
        $this->originQueryString = $queryString;
    }

    public function setTokens(array $tokens): void
    {
        $this->tokens = $tokens;
    }
    public function setSuggestions(array $suggestions): void
    {
        $this->suggestions = $suggestions;
    }
    public function getOriginQueryString(): string
    {
        return $this->originQueryString;
    }
    public function getTokens(): array
    {
        return $this->tokens;
    }
    public function getSuggestions(): array
    {
        return $this->suggestions;
    }

    public function getQueryString(bool $stopwords_enabled = false): string
    {
        $queryString = '';

        $stopwords = \Yii::$app->params['stopwords'];

        foreach ($this->getTokens() as $token) {
            if ($stopwords_enabled && in_array($token->tokenized, $stopwords)) {
                continue;
            }
            $queryString .= $token->tokenized . ' ';
        }

        return $queryString;
    }

    public function getSuggestionQueryString(bool $stopwords_enabled = false): string
    {
        $suggestionQueryString = '';

        $stopwords = \Yii::$app->params['stopwords'];

        foreach ($this->getSuggestions() as $suggestion) {
            if ($stopwords_enabled && in_array($suggestion->tokenized, $stopwords)) {
                continue;
            }
            $suggestionQueryString .= $suggestion->tokenized . ' ';
        }

        $queryString = $this->getQueryString($stopwords_enabled);

        return $suggestionQueryString === $queryString ? '' : $suggestionQueryString;
    }
}
