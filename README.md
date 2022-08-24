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
