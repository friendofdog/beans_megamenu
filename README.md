# beans_megamenu

Megamenu for Beans framework (http://www.getbeans.io/) for WordPress

## Issues / features to add

- ! Need a way to detect if Beans is installed and exit plugin if not (site could break otherwise)
- ! Need to be replaced with LESS variables that the user can change: ,@brand-color, @brand-color-light, @transition-speed, @contrast-font-color
- If either the CTA or main area is not populated, do not load that area and get rid of divider
- Site branding looks bad if it's text and not logo
- Need custom fields for menu editor (now using menu item Description and Title Attribute to determine uk-icon and on-hover text, respectively)
- Too much styling (mostly colouring) of .uk-navbar-nav class (and its children) is overwritten in MegaMenu LESS - consider letting user handle own styling
- CTA items wrap in an ugly way
- -17px right position not working on FF

## Changelog

0.1.1
- All colours are LESS variables
- Description section displays excerpt, link to parent, and list of its children
- 1px border between nav and megamenu
- -17px right position to mask scrollbar (not working on FF)

0.1.0
- Near-complete re-write of plugin
- Is now actually a plugin, can be used as such on WordPress

0.0.1
- Concept only, written for a test site
- Works, but everything's static