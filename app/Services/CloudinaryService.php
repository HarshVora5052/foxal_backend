<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CloudinaryService
{
    protected ?string $cloudName;
    protected ?string $apiKey;
    protected ?string $apiSecret;

    public function __construct()
    {
        $this->cloudName = config('services.cloudinary.cloud_name', env('CLOUDINARY_CLOUD_NAME'));
        $this->apiKey = config('services.cloudinary.api_key', env('CLOUDINARY_API_KEY'));
        $this->apiSecret = config('services.cloudinary.api_secret', env('CLOUDINARY_API_SECRET'));

        // If CLOUDINARY_URL is set (format: cloudinary://api_key:api_secret@cloud_name)
        $url = env('CLOUDINARY_URL');
        if ($url && Str::startsWith($url, 'cloudinary://') && (!$this->cloudName || !$this->apiKey)) {
            $parsed = parse_url($url);
            if ($parsed) {
                $this->apiKey = $parsed['user'] ?? $this->apiKey;
                $this->apiSecret = $parsed['pass'] ?? $this->apiSecret;
                $this->cloudName = $parsed['host'] ?? $this->cloudName;
            }
        }
    }

    public function upload(UploadedFile $file, string $folder = 'foxal/products'): string
    {
        if ($this->cloudName && $this->apiKey && $this->apiSecret && !Str::contains($this->apiKey, 'api_key')) {
            try {
                $timestamp = time();
                $paramsToSign = "folder={$folder}&timestamp={$timestamp}{$this->apiSecret}";
                $signature = sha1($paramsToSign);

                $response = Http::asMultipart()->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload", [
                    'file' => fopen($file->getRealPath(), 'r'),
                    'api_key' => $this->apiKey,
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                    'folder' => $folder,
                ]);

                if ($response->successful()) {
                    return $response->json('secure_url');
                }

                Log::warning('Cloudinary upload failed, falling back to local: ' . $response->body());
            } catch (Exception $e) {
                Log::error('Cloudinary error: ' . $e->getMessage());
            }
        }

        // Fallback to local public disk
        $filename = Str::random(24) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads/products', $filename, 'public');
        return url('/storage/' . $path);
    }
}
