Post to Social
=================

A WordPress plugin for auto-posting to social networks.

## Installation

Note that the plugin folder should be named `post-to-social`. This is because if the [GitHub Updater plugin](https://github.com/afragen/github-updater) is used to update this plugin, if the folder is named something other than this, it will get deleted, and the updated plugin folder with a different name will cause the plugin to be silently deactivated.

## Basic usage

1. Go to _Settings > Post to Social_ and check the settings.

## Filter hooks

* `sf_source_post_type_args` - Modify the default arguments for registering the source post type (passes the arguments)
* `sf_custom_field_details_box_args` - Modify the default arguments for registering the details custom fields (passes the arguments)
* `sf_source_title` - Modify the output for a sources's title (passes the formatted title, and the source details array)
* `sf_date_format` - Modify date formats (passes the formatted date, and the original date string)
* `sf_footnote` - Modify the markup output for each footnote (passes the footnote)
* `sf_compiled_source` - Modify the compiled markup output for a source (passes the current output, the source details array, and the format)
* `sf_jump_back_link_text` - Modify the glyph used for the 'jump back' link at the end of each footnote
