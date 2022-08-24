# WebP Quality Analysis

It's a testing environment for the WebP image with 82 quality.

## Quick Start
To get started with testing the project:
1. Fork the repository.
2. Clone the fork locally.
3. Start the development environment by running `wp-env start`.
4. Go to `http://localhost:8888/wp-admin/` in your browser.
5. Log in using `admin` and `password`.

## Useful commands

### Image Import Commands

Before using the command you need to configure the API credentials. To do this create a new `.wp-env.override.json` with configuration below. You can get an Unsplash Access Key by creating a developer account and app at [https://unsplash.com/developers](https://unsplash.com/developers).

```
{
    "config": {
        "WEBP_UNSPLASH_ENDPOINT": "https://api.unsplash.com",
        "WEBP_UNSPLASH_SEARCH_PHOTOS_PATH": "/search/photos",
        "WEBP_UNSPLASH_LIST_PHOTOS_PATH": "/photos",
        "UNSPLASH_ACCESS_KEY": "YOUR-API-KEY"
    }
}
```

To import images from particular category. For example, to import images Cartoon images run following command.

```
npm run wp-env run cli 'wp media unsplash --query=cartoon'
```

#### Supported parameters

**query:** Query for nature of image. For example, cartoon, illustration, nature etc.

**number:** Number of images to fetch in request. Default 10.

**page:** Page number to fetch the images from.

**width:** Width of image.

**height:** Height of image.

**quality:** Quality of the image. Used for compression.

### WebP analysis command
The WebP analysis can be run in the command line using the follow command.

```wp-env run cli wp webp-analysis run```

The command will run the WebP analysis and generate a CSV results file in the `./exports` folder with a unix timestamp filename prefix.

The command offers 2 options.

| Option | Description |
| --- | --- |
| `--preview` | Preview the results in an ASCII table. |
| `--limit=<limit>` | Limit the total attachments to analyse. Defaults to 500 when exporting a CSV. When the `--preview` flag is used the maximum limit is set to 5. |


#### Examples

```
# Preview results in an ASCII table.
wp-env run cli wp webp-analysis run --preview

# Limit results to 10 attachments
wp-env run cli wp webp-analysis run --limit=10
```

##### Example Results
```
+----+------------------------+--------------+-------+--------+---------------+---------------+-------------+
| ID | filename               | size         | width | height | jpeg_filesize | webp_filesize | larger_webp |
+----+------------------------+--------------+-------+--------+---------------+---------------+-------------+
| 6  | DSC05575-300x200.jpg   | medium       | 300   | 200    | 25525         | 23824         | 0           |
| 6  | DSC05575-1024x683.jpg  | large        | 1024  | 683    | 142435        | 114842        | 0           |
| 6  | DSC05575-150x150.jpg   | thumbnail    | 150   | 150    | 16708         | 15824         | 0           |
| 6  | DSC05575-768x512.jpg   | medium_large | 768   | 512    | 90597         | 76160         | 0           |
| 6  | DSC05575-1536x1024.jpg | 1536x1536    | 1536  | 1024   | 269440        | 192334        | 0           |
| 6  | DSC05575-2048x1365.jpg | 2048x2048    | 2048  | 1365   | 426133        | 284142        | 0           |
| 5  | IMG_1893-300x225.jpg   | medium       | 300   | 225    | 39521         | 39448         | 0           |
| 5  | IMG_1893-1024x768.jpg  | large        | 1024  | 768    | 352133        | 345334        | 0           |
| 5  | IMG_1893-150x150.jpg   | thumbnail    | 150   | 150    | 21650         | 21802         | 1           |
| 5  | IMG_1893-768x576.jpg   | medium_large | 768   | 576    | 200594        | 198830        | 0           |
| 5  | IMG_1893-1536x1152.jpg | 1536x1536    | 1536  | 1152   | 781539        | 755282        | 0           |
| 5  | IMG_1893-2048x1536.jpg | 2048x2048    | 2048  | 1536   | 1354462       | 1285978       | 0           |
+----+------------------------+--------------+-------+--------+---------------+---------------+-------------+
```
