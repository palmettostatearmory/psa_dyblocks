# Extension Overview

This extension is used for “dynamically” placing blocks of content on product pages based on product *attributes, SKU, category*, and/or *date range*.

Users can create these blocks in the Magento admin where they create the content and specify the conditions which determine which product pages the content will display on.


# Installation

In your theme's `template/catalog/product/view.phtml` file, add the following 3 snippets where you would like the block content to display:

`<?php echo $this->getLayout()->createBlock('psa_dyblocks/dyblock')->setPosition('product_notes_top')->toHtml(); ?>`

`<?php echo $this->getLayout()->createBlock('psa_dyblocks/dyblock')->setPosition('product_block_top')->toHtml(); ?>`

`<?php echo $this->getLayout()->createBlock('psa_dyblocks/dyblock')->setPosition('product_block_bottom')->toHtml(); ?>`

These positions (product_notes_top, product_block_top, product_block_bottom) are presented as options, when creating a block in the admin.


# Creating/Editing a Dynamic Block

1. In the Magento Admin, navigate to *CMS > PSA Dynamic CMS Blocks*.

2. To create a new block click the *Add New Dynamic Block* button, to edit an existing block click one in the list.

3. Fill-in the form, as necessary.  The *position on page* option locations will be determined by the placement, within the template, in **Installation**.

4. Conditions:  block will display if product matches ALL conditions.
