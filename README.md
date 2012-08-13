AddHeaderfiles snippet
================================================================================

Adds CSS or JS in a document (at the end of the head or the end of the body)
for the MODX Evolution content management framework

Features:
--------------------------------------------------------------------------------
With this snippet the MODX API functions regClientStartupScript, regClientScript and regClientCSS are used to insert Javascript and CSS-Styles at the appropriate positions of the current page
  
Installation:
--------------------------------------------------------------------------------
Upload the 'AddHeaderfiles' folder to 'assets/snippets' and create a snippet called AddHeaderfiles with the following code

```php
<?php
return include(MODX_BASE_PATH.'assets/snippets/AddHeaderfiles/AddHeaderfiles.snippet.php');
?>
```

Options:
--------------------------------------------------------------------------------
The default media type can be set in the snippet properties if the following code is inserted snippet call:

```
&mediadefault=Media default for css files;text;
```

The default media type can be changed by editing the corresponding line in the snippet code.

Parameters:
--------------------------------------------------------------------------------

Name | Description | Default
---- | ----------- | -------
addcode | external filenames(s) or chunkname(s) separated by &sep. The external files can have a position setting or media type separated by &sepmed, see note 1 | -
sep  | separator for files/chunknames | ;
sepmed  |  seperator for media type or script position | \|

Examples:
--------------------------------------------------------------------------------

### Direct call:

```
[!AddHeaderfiles?addcode=`/assets/js/jquery.js;/assets/js/colorbox.js|end;/assets/css/colorbox.css;/assets/css/test.css|print`!]
```

shows:

```html
...
    <script type="text/javascript" src="/assets/js/jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/css/colorbox.css" media="screen, tv, projection" />
    <link rel="stylesheet" type="text/css" href="/assets/css/test.css" media="print" />
</head>
...
    <script type="text/javascript" src="/assets/js/colorbox.js"></script>
</body>
```

### Chunk call:

Fill a chunk (i.e. 'headerColorbox') by:

```
/assets/js/jquery.js;/assets/js/colorbox.js|end;/assets/css/colorbox.css
```

and call it like this:

```
[!AddHeaderfiles?addcode=`headerColorbox`!]
```

Parts of the addcode parameterchain could point to chunks too (recursive). The parts of the cunks that are not pointing to other chunks ot to files/uri should contain the complete `<style>...</style>` or `<script>...</script>` code.

```
[!AddHeaderfiles?addcode=`headerColorbox;/assets/css/test.css|print`!]
```

Notes:
--------------------------------------------------------------------------------
1. If you want to insert external files with url parameters *directly* in the snippet call, some chars have to be masked. `?` has to be masked as `!q!`. `=` has to be masked as `!eq!`. `&` has to be masked as `!and!`. The chars don't have to be masked in chunks.
