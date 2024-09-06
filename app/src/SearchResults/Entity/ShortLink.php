<?php 

declare(strict_types=1);

namespace App\SearchResults\Entity;

class ShortLink 
{
    public string $short;
    public string $url;
    public string $search;
    public int $redirect_count;
    public string $created_at;
    
    public function __construct(
        string $short,
        string $url,
        string $search,
        int $redirect_count,
        string $created_at
    ) {
        $this->short = $short;
        $this->url = $url;
        $this->search = $search;
        $this->redirect_count = $redirect_count;
        $this->created_at = $created_at;
    }
}