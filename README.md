# Statamic Flexipics

> Statamic Flexipics is a powerful Statamic addon that generates responsive images using the HTML5 `<picture>` element with advanced breakpoint support, multiple image formats, and flexible configuration options.

![Statamic 4.0+](https://img.shields.io/badge/Statamic-4.0+-FF269E?style=for-the-badge&link=https://statamic.com)
![Packagist](https://img.shields.io/packagist/v/pktharindu/statamic-flexipics.svg?style=for-the-badge) 
![Packagist](https://img.shields.io/packagist/dt/pktharindu/statamic-flexipics.svg?style=for-the-badge)
![GitHub](https://img.shields.io/github/license/pktharindu/statamic-flexipics.svg?style=for-the-badge)

![Statamic 4.0+](https://img.shields.io/badge/Statamic-4.0+-FF269E?style=for-the-badge&link=https://statamic.com)
![Packagist](https://img.shields.io/packagist/v/pktharindu/statamic-flexipics.svg?style=for-the-badge) 
![Packagist](https://img.shields.io/packagist/dt/pktharindu/statamic-flexipics.svg?style=for-the-badge)
![GitHub](https://img.shields.io/github/license/pktharindu/statamic-flexipics.svg?style=for-the-badge)

## Features

Statamic Flexipics provides a comprehensive solution for responsive images in your Statamic projects:

- **Advanced Breakpoint Support**: Define custom breakpoints for different screen sizes with precise control over image dimensions
- **Multiple Output Formats**: Generate images in WebP and other formats with automatic format selection based on browser support
- **Flexible Configuration**: Extensive configuration options for breakpoints, image sizes, and more
- **Lazy Loading**: Built-in support for lazy loading images to improve page performance
- **Multiple Output Modes**: Generate HTML or JSON output for different use cases
- **Orientation Control**: Support for both landscape and portrait orientations
- **Automatic Alt Text**: Intelligent handling of alt text with configurable formatting
- **Device Pixel Ratio Support**: Generate images for different device pixel ratios (1x, 2x, etc.)
- **Size Multipliers**: Create multiple image sizes based on configurable multipliers
- **Caption Support**: Add captions to your images with ease
- **Minimal Changes Required**: Drop-in replacement for existing image tags with minimal template changes

## How to Install

You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, or run the following command from your project root:

``` bash
composer require pktharindu/statamic-flexipics
```

## How to Use

### Basic Usage

The simplest way to use Flexipics is with the `picture` tag:

```antlers
{{ picture:featured_image }}
```

This will generate a responsive `<picture>` element with default settings, where `featured_image` is a field handle containing the image.

### Advanced Usage

You can customize the output with various parameters:

```antlers
{{ picture:featured_image 
    class="my-custom-class"
    alt="{featured_image:alt ?? 'Custom alt text'}"
    lazy="true"
    sm="640"
    md="768|auto"
    lg="1024|16:9"
    xl="1280|4:3|100vw"
    default="320|16:9|100vw"
    output="html"
}}
```


### Parameters

The image is specified using the field handle in the tag name (e.g., `picture:featured_image`).

| Parameter                                             | Description                                             | Required | Default |
|-------------------------------------------------------|----------------------------------------------------------|----------|---------|
| `src`                                                 | URL or an image field handle                            | Yes      | None    |
| `class`                                               | CSS class to add to the image                           | No       | None    |
| `alt`                                                 | Custom alt text (falls back to asset's alt text)        | No       | Asset's alt text |
| `caption`                                             | HTML caption to display with the image                  | No       | None    |
| `lazy`                                                | Enable/disable lazy loading (true/false)                | No       | `true`  |
| `orientation`                                         | Image orientation (landscape/portrait)                  | No       | `landscape` |
| `default`                                             | Default size for the image (format: `width\|ratio\|sizes`) | No       | None    |
| Breakpoint parameters (`sm`, `md`, `lg`, `xl`, `2xl`) | Configure sizes for specific breakpoints                | No       | None    |
| `output`                                              | Output format (html, json)                              | No       | `html`  |

## Configuration

Flexipics comes with a comprehensive configuration file that you can publish and customize:

```bash
php artisan vendor:publish --tag=statamic-flexipics-config
```

This will create a `config/statamic/flexipics.php` file with the following options:

### Breakpoints

Define the screen sizes at which your responsive images should change:

```php
'breakpoints' => [
    'default' => 0,
    'sm' => 640,
    'md' => 768,
    'lg' => 1024,
    'xl' => 1280,
    '2xl' => 1536,
],
```

### Size Multipliers

Define the multipliers for generating different image sizes:

```php
'size_multipliers' => [1, 1.5, 2],
```

When using the default multipliers and requesting an image in 300px, the tag generates sizes in 300px, 450px, and 600px.

### Device Pixel Ratios

Define for which device pixel ratios (DPR) sources should be generated when not using the `sizes` attribute:

```php
'dpr' => [1, 2],
```

### Default Filetype

Set the default image format for generating sources:

```php
'default_filetype' => 'webp',
```

### Minimum Image Width

Define the smallest width to use for image processing:

```php
'min_width' => 300,
```

### Lazy Loading Default

Enable or disable lazy loading by default:

```php
'lazy_loading' => true,
```

### Original Format Fallback

Enable or disable using the original format as a fallback when the desired format is not available:

```php
'use_original_format_as_fallback' => false,
```

### Alt Text Formatting

Ensure alt text always ends with a period:

```php
'alt_fullstop' => true,
```

## Performance Benefits

Flexipics is designed with performance in mind:

- **Optimized Image Loading**: By generating responsive images with appropriate sizes for each device, Flexipics reduces bandwidth usage and improves page load times.
- **WebP Support**: Default WebP format provides better compression than JPEG or PNG while maintaining high quality.
- **Lazy Loading**: Built-in lazy loading ensures images are only loaded when they enter the viewport, improving initial page load performance.
- **Efficient Caching**: Generated images are cached for optimal performance.
- **Minimal Overhead**: The package is designed to be lightweight and efficient, with minimal impact on your site's performance.

## Why Choose Flexipics?

- **Complete Control**: Fine-grained control over image sizes, formats, and breakpoints.
- **Developer-Friendly**: Simple API with sensible defaults that can be easily customized.
- **Modern Standards**: Uses modern HTML5 `<picture>` element for maximum browser compatibility.
- **Statamic Integration**: Seamlessly integrates with Statamic's asset system.
- **Flexible Output**: Multiple output formats (HTML, JSON) for different use cases.
- **Extensively Tested**: Comprehensive test suite ensures reliability.

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

Statamic Flexipics is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.
