<?php namespace Buchin\Youtube;
use Buzz\Browser;

class Youtube {

	var $url = 'http://gdata.youtube.com/feeds/api/videos?alt=json&v=2';

	public function __construct(Browser $browser)
	{
		$this->browser = $browser;
	}

	public function search($term = '', $max_results = 25, $start_index = 1)
	{
		$videos = array();
		$term = urlencode($term);
		$url = $this->url . '&q=' . $term;
		$url = $url . '&max-results=' . $max_results;
		$url = $url . '&start-index=' . $start_index;

		$response = $this->browser->get($url);

		$response = json_decode($response, true);
		
		$meta = array();
		$meta['max_results'] = $response['feed']['openSearch$itemsPerPage']['$t'];
		$meta['start_index'] = $response['feed']['openSearch$startIndex']['$t'];
		$meta['total_results'] = $response['feed']['openSearch$totalResults']['$t'];

		$response = isset($response['feed']['entry'][0]) ? $response['feed']['entry'] : null;

		foreach ($response as $raw) {
			$videos[] = $this->format($raw);
		}

		return array(
			'meta' => $meta,
			'videos' => $videos
			);
	}

	public function format($raw)
	{
		$video = array();
		$video['id'] = $raw['media$group']['yt$videoid']['$t'];
		$video['author'] = $raw['author'][0]['name']['$t'];
		$video['title'] = $raw['title']['$t'];
		$video['excerpt'] = $raw['media$group']['media$description']['$t'];
		$video['views'] = $raw['yt$statistics']['viewCount'];
		$video['likes'] = $raw['yt$rating']['numLikes'];
		$video['aspect_ratio'] = isset($raw['media$group']['yt$aspectRatio']['$t']) ? $raw['media$group']['yt$aspectRatio']['$t'] : 'unknown';
		$video['duration'] = $raw['media$group']['yt$duration']['seconds'];
		$video['thumbnail'] = $raw['media$group']['media$thumbnail'][0]['url'];
		$video['thumbnail_mq'] = $raw['media$group']['media$thumbnail'][1]['url'];
		$video['thumbnail_hq'] = $raw['media$group']['media$thumbnail'][2]['url'];
		$video['thumbnail_sd'] = $raw['media$group']['media$thumbnail'][3]['url'];
		return $video;
	}
}