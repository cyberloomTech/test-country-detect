# PHP Country Detector

A simple PHP project that detects the user's country based on their IP address and maps it to a language code.

## Features

- Detects user IP address (supports Cloudflare)
- Uses ipapi.co API for geolocation
- Maps countries to language codes
- Clean, responsive UI

## Usage

1. Make sure you have PHP installed
2. Run the built-in PHP server:
   ```
   php -S localhost:8000
   ```
3. Open your browser and visit: `http://localhost:8000`

## API

This project uses the free [ipapi.co](https://ipapi.co/) API for IP geolocation. No API key required for basic usage.

## Supported Countries

The project includes language mappings for: US, GB, PK, FR, DE, ES, IT, PT, AE, TR, RU, JP, CN, MY
