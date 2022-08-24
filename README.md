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

Import images from particular category. For example, to import images Cartoon images run following command

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