<?php
$apiKey = "AIzaSyCYlMSAYtUG78S5cx_tf1dajqWrc6X_K1k"; 
$query = urlencode("suitorguy trivandram");
$textSearchUrl = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=$query&key=$apiKey";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $textSearchUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$textSearchResponse = curl_exec($ch);
curl_close($ch);

$textSearchData = json_decode($textSearchResponse, true);

if (isset($textSearchData['results'])) {
    foreach ($textSearchData['results'] as $place) {
        $placeId = $place['place_id'];
        echo "Fetching details for Place ID: $placeId\n";

        $detailsUrl = "https://maps.googleapis.com/maps/api/place/details/json?place_id=$placeId&fields=name,rating,reviews&key=$apiKey";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $detailsUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $detailsResponse = curl_exec($ch);
        curl_close($ch);

        $detailsData = json_decode($detailsResponse, true);

        if (isset($detailsData['result'])) {
            $name = $detailsData['result']['name'] ?? "Unknown";
            $rating = $detailsData['result']['rating'] ?? "No rating";
            echo "Name: $name\n";
            echo "Rating: $rating\n";

            if (isset($detailsData['result']['reviews'])) {
                echo "Reviews:\n";
                foreach ($detailsData['result']['reviews'] as $review) {
                    echo "- " . $review['text'] . " (Rating: " . $review['rating'] . ")\n";
                }
            } else {
                echo "No reviews available.\n";
            }
            echo "\n";
        }
    }
} else {
    echo "No places found.";
}
?>
