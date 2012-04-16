AddHeaderfiles snippet
================================================================================

Adds CSS or JS in a document (at the end of the head or the end of the body)
for the MODX Evolution content management framework

Features:
--------------------------------------------------------------------------------
With this snippet the MODX API functions regClientStartupScript, regClientScript and regClientCSS are used to insert Javascript and
CSS-Styles at the appropriate positions of the current page
  
Installation:
--------------------------------------------------------------------------------
Upload the 'AddHeaderfiles' folder to 'assets/snippets' and create a snippet called AddHeaderfiles with the following code

    <?php
    return include(MODX_BASE_PATH.'assets/snippets/AddHeaderfiles/AddHeaderfiles.snippet.php');
    ?>

Options:
--------------------------------------------------------------------------------
The default media type can be set in the snippet properties if the following code is inserted snippet call:

    &mediadefault=Media default for css files;text;

The default media type can be changed by editing the corresponding line in the snippet code.

Parameters:
--------------------------------------------------------------------------------

Name | Description | Default
---- | ----------- | -------
addcode | external filenames(s) or chunkname(s) separated by &sep these external files can have a position setting or media type separated by &sepmed | -
sep  | separator for files/chunknames | ;
sepmed  |  seperator for media type or script position | \|

Examples:
--------------------------------------------------------------------------------

### Direct call:

    [!AddHeaderfiles?addcode=`/manager/media/script/mootools/mootools.js;/assets/js/slimbox.js|end;/assets/css/slimbox.css;/assets/css/test.css|print`!]

shows:
```
...
    <script type="text/javascript" src="/manager/media/script/mootools/mootools.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/css/slimbox.css" media="screen, tv, projection" />
    <link rel="stylesheet" type="text/css" href="/assets/css/test.css" media="print" />
</head>
...
    <script type="text/javascript" src="/assets/js/slimbox.js"></script>
</body>

### Chunk call:
Fill a chunk (i.e. 'headerSlimbox') by:

    /assets/js/mootools.js;/assets/js/slimbox.js;/assets/css/slimbox.css

and call it like this:

    [!AddHeaderfiles?addcode=`headerSlimbox`!]

Parts of the addcode parameterchain could point to chunks too (recursive).
These chunks should contain the complete `<style>...</style>` or
`<script>...</script>` code.

    [!AddHeaderfiles?addcode=`headerSlimbox;/assets/css/test.css|print`!]