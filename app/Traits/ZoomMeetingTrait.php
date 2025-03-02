<?php

namespace App\Traits;

use DateTime;
use DateTimeZone;
use Exception;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Log;
use Psr\Http\Message\ResponseInterface;

/**
 * trait ZoomMeetingTrait
 */
trait ZoomMeetingTrait
{
    public $client;

    public $jwt;

    public $headers;

    public function __construct()
    {
        $this->client = new Client();
        $this->jwt = $this->generateZoomToken();
        $this->headers = [
            'Authorization' => 'Bearer '.$this->jwt,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    public function generateZoomToken(): string
    {
        $key = env('ZOOM_API_KEY', '');
        $secret = env('ZOOM_API_SECRET', '');
        $payload = [
            'iss' => $key,
            'exp' => strtotime('+1 minute'),
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }

    private function retrieveZoomUrl()
    {
        return env('ZOOM_API_URL', '');
    }

    /**
     * @throws GuzzleException
     */
    public function zoomGet(string $path, array $data = []): ResponseInterface
    {
        $url = $this->retrieveZoomUrl();
        $this->jwt = $this->generateZoomToken();
        $body = [
            'headers' => $this->headers,
            'body' => json_encode($data),
        ];

        return $this->client->get($url.$path, $body);
    }

    /**
     * @throws GuzzleException
     */
    public function zoomPost(string $path, array $data = []): ResponseInterface
    {
        $url = $this->retrieveZoomUrl();

        $body = [
            'headers' => $this->headers,
            'body' => json_encode($data),
        ];

        return $this->client->post($url.$path, $body);
    }

    /**
     * @throws GuzzleException
     */
    public function zoomPatch(string $path, array $data = []): ResponseInterface
    {
        $url = $this->retrieveZoomUrl();

        $body = [
            'headers' => $this->headers,
            'body' => json_encode($data),
        ];

        return $this->client->patch($url.$path, $body);
    }

    /**
     * @throws GuzzleException
     */
    public function zoomDelete(string $path, array $data = []): ResponseInterface
    {
        $url = $this->retrieveZoomUrl();
        $body = [
            'headers' => $this->headers,
            'body' => json_encode($data),
        ];

        return $this->client->delete($url.$path, $body);
    }

    public function toZoomTimeFormat(string $dateTime): string
    {
        try {
            $date = new DateTime($dateTime);

            return $date->format('Y-m-d\TH:i:s');
        } catch (Exception $e) {
            Log::error('ZoomJWT->toZoomTimeFormat : '.$e->getMessage());

            return '';
        }
    }

    public function toUnixTimeStamp(string $dateTime, string $timezone)
    {
        try {
            $date = new DateTime($dateTime, new DateTimeZone($timezone));

            return $date->getTimestamp();
        } catch (Exception $e) {
            Log::error('ZoomJWT->toUnixTimeStamp : '.$e->getMessage());

            return '';
        }
    }

    /**
     * @throws GuzzleException
     */
    public function create($data): array
    {
        $path = 'users/me/meetings';
        $response = $this->zoomPost($path, [
            'topic' => $data['topic'],
            'type' => self::MEETING_TYPE_SCHEDULE,
            'start_time' => $this->toZoomTimeFormat($data['start_time']),
            'duration'   => $data['duration'],
            'agenda'     => (!empty($data['agenda'])) ? $data['agenda'] : null,
            'timezone'   => $data['time_zone'],
            'settings'   => [
                'host_video'        => ($data['host_video'] == '1') ? true : false,
                'participant_video' => ($data['participant_video'] == '1') ? true : false,
                'waiting_room' => true,
            ],
        ]);

        return [
            'success' => $response->getStatusCode() === 201,
            'data' => json_decode($response->getBody(), true),
        ];
    }

    /**
     * @param $id
     * @param $data
     * @throws GuzzleException
     * @return array
     */
    public function update($id, $data): array
    {
        $path = 'meetings/'.$id;
        $response = $this->zoomPatch($path, [
            'topic' => $data['topic'],
            'type' => self::MEETING_TYPE_SCHEDULE,
            'start_time' => $this->toZoomTimeFormat($data['start_time']),
            'duration'   => $data['duration'],
            'agenda'     => (!empty($data['agenda'])) ? $data['agenda'] : null,
            'timezone'   => $data['time_zone'],
            'settings'   => [
                'host_video'        => ($data['host_video'] == '1') ? true : false,
                'participant_video' => ($data['participant_video'] == '1') ? true : false,
                'waiting_room' => true,
            ],
        ]);

        return [
            'success' => $response->getStatusCode() === 204,
            'data' => json_decode($response->getBody(), true),
        ];
    }

    /**
     * @param $id
     * @throws GuzzleException
     * @return array
     */
    public function get($id): array
    {
        $path = 'meetings/'.$id;
        $response = $this->zoomGet($path);

        return [
            'success' => $response->getStatusCode() === 204,
            'data' => json_decode($response->getBody(), true),
        ];
    }

    /**
     * @param string $id
     * @throws GuzzleException
     * @return bool[]
     */
    public function delete($id): array
    {
        $path = 'meetings/'.$id;
        $response = $this->zoomDelete($path);

        return [
            'success' => $response->getStatusCode() === 204,
        ];
    }
}
