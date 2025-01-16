<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RssService
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Récupère et traite le flux RSS
     *
     * @param string $rssUrl Lien du flux RSS
     * @return array Tableau des éléments du flux RSS
     * @throws \Exception Si le flux ne peut pas être récupéré ou parsé
     */
    public function fetchRssFeed(string $rssUrl): array
    {
        try {
            // Récupération du contenu RSS
            $response = $this->httpClient->request('GET', $rssUrl);
            $rssContent = $response->getContent();

            // Parse le contenu RSS
            $rss = simplexml_load_string($rssContent, 'SimpleXMLElement', LIBXML_NOCDATA);
            if (!$rss || !isset($rss->channel->item)) {
                throw new \Exception('Le flux RSS est invalide ou vide.');
            }

            // Parcours des items du flux RSS
            $items = [];
            foreach ($rss->channel->item as $item) {
                $items[] = [
                    'title' => (string) $item->title,
                    'link' => (string) $item->link,
                    'description' => (string) $item->description,
                    'pubDate' => (string) $item->pubDate,
                    'categories' => array_map('strval', iterator_to_array($item->category ?? [])),
                ];
            }

            return $items;

        } catch (\Exception $e) {
            // Gestion des exceptions
            throw new \Exception('Erreur lors de la récupération du flux RSS : ' . $e->getMessage());
        }
    }

    public function RssDomimmo(string $rssUrl): array
    {
        try {
            // Fetch the RSS feed content
            $response = $this->httpClient->request('GET', $rssUrl);
            $rssContent = $response->getContent();

            // Parse the RSS content
            $rss = simplexml_load_string($rssContent, 'SimpleXMLElement', LIBXML_NOCDATA);
            if (!$rss || !isset($rss->channel->item)) {
                throw new \Exception('Le flux RSS est invalide ou vide.');
            }

            // Iterate over the items in the RSS feed
            $items = [];
            foreach ($rss->channel->item as $item) {
                $items[] = [
                    'title' => (string) $item->title,             // Property name
                    'link' => (string) $item->link,               // Link to the listing
                    'description' => (string) $item->description, // Description
                    'price' => (string) preg_replace('/[^0-9]/', '', $item->title), // Extract price from title
                    'address' => (string) $item->description,     // Address in the description
                    'image' => (string) $item->enclosure['url'],  // Image URL from enclosure tag
                    'pubDate' => (string) $item->pubDate,         // Publication date
                ];
            }

            return $items;

        } catch (\Exception $e) {
            // Handle exceptions
            throw new \Exception('Erreur lors de la récupération du flux RSS : ' . $e->getMessage());
        }
    }

}
