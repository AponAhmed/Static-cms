# Static CMS

## Overview
Static CMS is a content management system developed in PHP, specifically designed for static websites. It provides a simple and efficient way to manage content, menus, and media for your static web pages.

## Features
- **Dynamic Content:** Easily manage and update content for your static pages.
- **Media Management:** Organize and handle media files for your website.
- **SEO Optimization:** Implement SEO-friendly practices with meta tags and page-specific settings.

## Technologies Used
- PHP
- HTML
- CSS
- JavaScript

## Directory and file Structure

```
root
├── contents
│   ├── data
│   ├── media
│   └── themes
├── manage
│   ├── assets
│   └── view
├── src
│   ├── functions
│   │   ├── admin-function.php
│   │   ├── admin-metabox.php
│   │   ├── function-third.php
│   │   ├── hook.php
│   │   ├── seo.php
│   │   └── template.php
│   ├── manage
│   │   ├── Controllers
│   │   │   ├── CartController.php
│   │   │   ├── ContactsController.php
│   │   │   ├── Controller.php
│   │   │   ├── FooterController.php
│   │   │   ├── HomeController.php
│   │   │   ├── ImagebrowseController.php
│   │   │   ├── LoginController.php
│   │   │   ├── LogoutController.php
│   │   │   ├── MediaController.php
│   │   │   ├── MenuController.php
│   │   │   ├── PagesController.php
│   │   │   ├── SettingsController.php
│   │   │   ├── SitemapController.php
│   │   │   ├── SlidersController.php
│   │   │   ├── StaticController.php
│   │   │   ├── SysupdateController.php
│   │   │   ├── TaxonomyController.php
│   │   │   └── ThemesController.php
│   │   └── Model
│   │       ├── Media.php
│   │       ├── MediaCategory.php
│   │       ├── Menu.php
│   │       ├── Model.php
│   │       ├── Page.php
│   │       ├── Settings.php
│   │       ├── Slider.php
│   │       ├── StaticPage.php
│   │       └── Taxonomy.php
│   ├── shortcode
│   │   ├── LinkShortcode.php
│   │   ├── RandkeyShortcode.php
│   │   ├── RandphraseShortcode.php
│   │   ├── SitemapShortcode.php
│   │   ├── SliderShortcode.php
│   │   ├── StaticShortcode.php
│   │   ├── WriteusShortcode.php
│   │   ├── BoldTagCode.php
│   │   ├── ColorTagCode.php
│   │   ├── ContactShortcode.php
│   │   ├── ExlinkShortcode.php
│   │   ├── ExpandTagCode.php
│   │   ├── GridTagCode.php
│   │   ├── ImageShortcode.php
│   │   └── InnerLinkBuilder.php
│   └── Utilities
│       ├── SitemapGenerator.php
│       ├── ApiRequest.php
│       ├── BuiltIn.php
│       ├── Cart.php
│       ├── CsvFileManager.php
│       ├── FrontendControll.php
│       ├── MetaBox.php
│       ├── MetaBoxs.php
│       └── Singleton.php
├── .env
├── .htaccess
└── index.php

```
# Documentation 

## Functions in Functions.php (File: /src/Functions.php)

### Global Helper Functions

1. **urlSlashFix($url)**
   - **Description:** Fixes multiple slashes in a URL.
   - **Parameters:** $url (string) - The input URL.
   - **Returns:** The URL with corrected slashes.

2. **getGlobal($index)**
   - **Description:** Retrieves a value from the global $GLOBALS['STCMS'] array, with the option to provide a default value if the index doesn't exist.
   - **Parameters:** $index (string) - The index/key to retrieve from the global array.
   - **Returns:** The value stored at the provided index, or false if not found.

3. **app()**
   - **Description:** Retrieves the application instance using the getGlobal() function for the 'app' index.
   - **Returns:** The application instance.

4. **is_404()**
   - **Description:** Checks if the current page is a 404 error page.
   - **Returns:** true if it's a 404 page, otherwise false.

### Template Functions

5. **is_home()**
   - **Description:** Checks whether the current page is the home page of the website.
   - **Implementation:** ...
   - **Returns:**
      - true if the current page is the home page.
      - false if the current page is not the home page.
   - **Usage Example:**
     ```php
     if (is_home()) {
         echo "Welcome to the home page!";
     } else {
         echo "You are not on the home page.";
     }
     ```

6. **get_content()**
   - **Description:** Retrieves the content of the current page.
   - **Returns:** The content of the current page.

7. **page()**
   - **Description:** Retrieves the current page object.
   - **Returns:** The current page object.

8. **get_breadcrumb()**
   - **Description:** Generates and displays a breadcrumb trail for the current web page.
   - **Usage:** `get_breadcrumb();`

9. **get_breadcrumb_json()**
   - **Description:** Generates JSON-LD structured data for breadcrumbs, suitable for search engines and structured data usage.
   - **Usage:** `get_breadcrumb_json();`

10. **get_breadcrumb_items()**
    - **Description:** Retrieves an array of breadcrumb items based on the URL segments of the current page.
    - **Returns:** An array of breadcrumb items.
    - **Usage:** `$breadcrumb_items = get_breadcrumb_items();`

11. **get_thumbnail($size = false, $random = false)**
    - **Description:** Retrieves and displays the thumbnail image associated with the current page.
    - **Parameters:**
      - $size (mixed) - The size of the thumbnail.
      - $random (boolean) - Whether to choose a random thumbnail from available options.
    - **Usage Examples:**
      - Fixed Size: `get_thumbnail(300);`
      - Responsive Size: `get_thumbnail([550, 400, 300]);`
      - Responsive Size with Column Basis: `get_thumbnail([6, 6, 12], true);`

12. **get_title()**
    - **Description:** Retrieves the title of the current page.
    - **Returns:** The title of the current page.

13. **the_content()**
    - **Description:** Displays the content of the current page.

14. **get_menu($name, $arg = [])**
    - **Description:** Retrieves and displays a menu by its name.
    - **Parameters:**
      - $name (string) - The name of the menu.
      - $arg (array) - Additional arguments for customizing the menu display.

15. **slug_text()**
    - **Description:** Retrieves the slug text from the global context.
    - **Returns:** The slug text.

16. **site_name()**
    - **Description:** Retrieves the site name from the application settings.
    - **Returns:** The site name.

17. **site_url()**
    - **Description:** Retrieves the site URL from the application settings.
    - **Returns:** The site URL.

18. **site_logo($width = 200, $mobileWidth = false)**
    - **Description:** Retrieves and displays the site logo image.
    - **Parameters:**
      - $width (int) - The width of the logo image.
      - $mobileWidth (mixed) - The width of the logo image on mobile devices (optional).
    - **Returns:** The HTML for the logo image.

19. **site_icon(...$sizes)**
    - **Description:** Outputs the HTML for the site favicon with multiple sizes.
    - **Parameters:** Variable number of sizes for the favicon.
    - **Outputs:** HTML link elements for the favicon.

20. **get_header()**
    - **Description:** Includes the header file of the current theme.

21. **get_footer()**
    - **Description:** Includes the footer file of the current theme.

22. **theme_uri($path = "")**
    - **Description:** Retrieves the URI for a theme asset, like styles or scripts.
    - **Parameters:** $path (string) - The path to the asset within the theme.
    - **Returns:** The URL of the theme asset.

23. **get_attachment($size = false)**
    - **Description:** Retrieves and displays an attachment associated with the current page.
    - **Parameters:** $size (mixed) - The size of the attachment.
    - **Outputs:** The HTML for the attachment image.

24. **inline_theme_asset($path = '')**
    - **Description:** Inlines and outputs the content of theme assets such as CSS and JavaScript files directly into the HTML output of a web page.
    - **Parameters:**
      - $path (string, optional) - The relative path to the theme asset file.
    - **Usage Example:**
      ```php
      // Inline a CSS file named 'styles.css' from the theme directory.
      inline_theme_asset('styles.css');
      // Inline a JavaScript file named 'script.js' from the theme directory.
      inline_theme_asset('script.js');
      ```
    - **Note:** This function is useful for including small CSS or JavaScript files directly within the HTML for performance or other specific requirements.

### Functions in hook.php (File: /src/functions/hook.php)

24. **add_action($tag, $callback, $priority = 10, $accepted_args = 1)**
    - **Description:** Adds a callback function to an action hook.
    - **Parameters:**
      - $tag (string) - The hook to which the function is added.
      - $callback (callable) - The function to be called when the hook is triggered.
      - $priority (int) - The priority of the callback (optional, default is 10).
      - $accepted_args (int) - The number of arguments the callback accepts (optional, default is 1).
    - **Returns:** True if the action was successfully added.

25. **do_action($tag, ...$args)**
    - **Description:** Executes all functions hooked to a specified action hook.
    - **Parameters:**
      - $tag (string) - The hook to execute functions from.
      - $args (mixed) - Additional arguments to pass to the hooked functions.
    - **Returns:** The result of the action execution.

26. **add_filter($tag, $callback, $priority = 10, $accepted_args = 1)**
    - **Description:** Adds a callback function to a filter hook.
    - **Parameters:**
      - $tag (string) - The hook to which the function is added.
      - $callback (callable) - The function to be called when the hook is triggered.
      - $priority (int) - The priority of the callback (optional, default is 10).
      - $accepted_args (int) - The number of arguments the callback accepts (optional, default is 1).
    - **Returns:** True if the filter was successfully added.

27. **apply_filters($tag, $value, ...$args)**
    - **Description:** Applies all functions hooked to a specified filter hook.
    - **Parameters:**
      - $tag (string) - The hook to execute functions from.
      - $value (mixed) - The value to filter.
      - $args (mixed) - Additional arguments to pass to the hooked functions.
    - **Returns:** The filtered value after applying all filters.

### Functions in seo.php (File: /src/functions/seo.php)

28. **meta_title()**
    - **Description:** Retrieves the meta title for the current page.
    - **Returns:** The meta title of the current page.

29. **meta_description()**
    - **Description:** Retrieves the meta description for the current page.
    - **Returns:** The meta description of the current page.

30. **meta_keywords()**
    - **Description:** Retrieves the meta keywords for the current page.
    - **Returns:** The meta keywords of the current page.

31. **get_url()**
    - **Description:** Retrieves the URL for the current page.
    - **Returns:** The URL of the current page.

32. **seo_meta()**
    - **Description:** Generates and outputs HTML meta tags for SEO purposes based on the current page's metadata.
    - **Returns:** Outputs the HTML meta tags.

### Functions in template.php (File: /src/functions/template.php)

33. **is_admin()**
    - **Description:** Determines if the current user is in an administrative context.
    - **Returns:** true if the user is in an admin context, otherwise false.

34. **get_content()**
    - **Description:** Retrieves the content of the current page.
    - **Returns:** The content of the current page.

35. **page()**
    - **Description:** Retrieves the current page object.
    - **Returns:** The current page object.

36. **get_thumbnail($size = false, $random = false)**
    - **Description:** Retrieves and displays the thumbnail image associated with the current page.
    - **Parameters:**
      - $size (mixed) - The size of the thumbnail.
      - $random (boolean) - Whether to choose a random thumbnail from available options.
    - **Outputs:** The HTML for the thumbnail image.

37. **get_title()**
    - **Description:** Retrieves the title of the current page.
    - **Returns:** The title of the current page.

38. **the_content()**
    - **Description:** Displays the content of the current page.

39. **get_menu($name, $arg = [])**
    - **Description:** Retrieves and displays a menu by its name.
    - **Parameters:**
      - $name (string) - The name of the menu.
      - $arg (array) - Additional arguments for customizing the menu display.

40. **slug_text()**
    - **Description:** Retrieves the slug text from the global context.
    - **Returns:** The slug text.

41. **site_name()**
    - **Description:** Retrieves the site name from the application settings.
    - **Returns:** The site name.

42. **site_url()**
    - **Description:** Retrieves the site URL from the application settings.
    - **Returns:** The site URL.

43. **site_logo($width = 200, $mobileWidth = false)**
    - **Description:** Retrieves and displays the site logo image.
    - **Parameters:**
      - $width (int) - The width of the logo image.
      - $mobileWidth (mixed) - The width of the logo image on mobile devices (optional).
    - **Returns:** The HTML for the logo image.

44. **site_icon(...$sizes)**
    - **Description:** Outputs the HTML for the site favicon with multiple sizes.
    - **Parameters:** Variable number of sizes for the favicon.
    - **Outputs:** HTML link elements for the favicon.

45. **get_header()**
    - **Description:** Includes the header file of the current theme.

46. **get_footer()**
    - **Description:** Includes the footer file of the current theme.

47. **theme_uri($path = "")**
    - **Description:** Retrieves the URI for a theme asset, like styles or scripts.
    - **Parameters:** $path (string) - The path to the asset within the theme.
    - **Returns:** The URL of the theme asset.

48. **get_attachment($size = false)**
    - **Description:** Retrieves and displays an attachment associated with the current page.
    - **Parameters:** $size (mixed) - The size of the attachment.
    - **Outputs:** The HTML for the attachment image.

49. **Functions in admin-functions.php (File: /src/functions/admin-functions.php)**

50. **is_admin()**
    - **Description:** Determines if the current user is in an administrative context.
    - **Returns:** true if the user is in an admin context, otherwise false.

51. **urlSlashFix($url)**
    - **Description:** Fixes multiple slashes in a URL.
    - **Parameters:** $url (string) - The input URL.
    - **Returns:** The URL with corrected slashes.

52. **getGlobal($index)**
    - **Description:** Retrieves a value from the global $GLOBALS['STCMSADMIN'] array, with the option to provide a default value if the index doesn't exist.
    - **Parameters:** $index (string) - The index/key to retrieve from the global array.
    - **Returns:** The value stored at the provided index, or false if not found.

53. **siteUrl()**
    - **Description:** Retrieves the site URL from the global context.
    - **Returns:** The site URL.

54. **__e($var)**
    - **Description:** Outputs the provided variable safely.
    - **Parameters:** $var (mixed) - The variable to output.

55. **getMessage()**
    - **Description:** Retrieves and displays notification messages.
    - **Outputs:** The HTML for the notification messages.

56. **admin_header()**
    - **Description:** Includes the admin header file.

57. **admin_footer()**
    - **Description:** Includes the admin footer file.

58. **admin_sidebar()**
    - **Description:** Includes the admin sidebar file.

59. **admin_topbar()**
    - **Description:** Includes the admin topbar file.

60. **admin_url()**
    - **Description:** Retrieves the admin URL from the global context.
    -
61. `menuSelect($name, $current)`
   - **Description:** Generates an HTML select element with menu options.
   - **Parameters:**
     - `$name` (string) - The name attribute of the select element.
     - `$current` (mixed) - The current selection.
   - **Returns:** The HTML for the select element.

62. `admin_assets($path)`
   - **Description:** Retrieves the URL for an admin asset.
   - **Parameters:** `$path` (string) - The path to the asset within the admin assets directory.
   - **Returns:** The URL of the admin asset.

63. `UploadSubdirSet($segments)`
   - **Description:** Sets the subdirectory path for media uploads based on URL segments.
   - **Parameters:** `$segments` (array) - The URL segments.
   - **Returns:** The subdirectory path for media uploads.

### Functions in admin-metabox.php (File: /src/functions/admin-metabox.php)

64. *(Function documentation not provided as per the user's request.)*
   - The documentation for this function is not provided. If needed, you may add documentation for this function based on its implementation.



## Getting Started
Follow the steps below to set up and run the Static CMS:

### Prerequisites
- PHP installed on your server

### Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/AponAhmed/Static-cms.git
