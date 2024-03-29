<?php
return [
// Heading
'heading_title'=> 'Role',
// Text
'text_edit'=> 'Edit Setting',
'text_image'=> 'Select Image',
'text_clear'=> 'Clear',
'text_success'=> 'Success: You have modified role!',

// Entry
'entry_title'                      => 'Site Title',
'entry_tagline'=> 'Site Tagline',
'entry_logo'                    	=> 'Site Logo',
'entry_icon'=> 'Site Icon',
'entry_meta_title'                 => 'Meta Title',
'entry_meta_description'           => 'Meta Tag Description',
'entry_meta_keyword'               => 'Meta Tag Keywords',

'entry_site_owner'                 => 'Site Owner',
'entry_address'                 	=> 'Address',
'entry_country'=> 'Country',
'entry_state'                      => 'Region / State',
'entry_email'                      => 'E-Mail',
'entry_telephone'                  => 'Telephone',
'entry_fax'=> 'Fax',

'entry_library_fine'               => 'Fine',
'entry_issue_limit_books'          => 'Issue Limit - Books ',
'entry_issue_limit_days'           => 'Issue Limit - Days ',
'entry_auto_fine'                  => 'Automatic Fine',
'entry_receipt_prefix'             => 'Receipt Prefix',
'entry_display_stock'              => 'Book Stock Display',
'entry_stock_warning'              => 'Book Stock Warning',
'entry_mail_alert'              	=> 'Mail Alert',
'entry_sms_alert'              		=> 'SMS Alert',
'entry_delay_members'             	=> 'Delay members Warning',

'entry_site_homepage'              => 'Site Homepage',
'entry_front_theme'                => 'Front Theme',
'entry_front_template'             => 'Front Default Layout',
'entry_header_layout'              => 'Header Layout',
'entry_header_image'               => 'Header Image',
'entry_header_banner'              => 'Header Banner',
'entry_header_slider'              => 'Header Slider',
'entry_background_image'           => 'Background Image',
'entry_background_position'        => 'Background Position',
'entry_background_repeat'          => 'Background Repeat',
'entry_background_attachment'      => 'Background Attachment',
'entry_background_color'      	  => 'Background Color',
'entry_text_color'      			  => 'Text Color',

'entry_ftp_host'      			  => 'FTP Host',
'entry_ftp_port'      			  => 'FTP Port',
'entry_ftp_username'      		  => 'FTP Username',
'entry_ftp_password'      		  => 'FTP Password',
'entry_ftp_root'      			  => 'FTP Root',
'entry_ftp_enable'      			  => 'FTP Enable',

'entry_mail_protocol'      		  => 'Mail Protocol',
'entry_mail_parameter'      		  => 'Mail Parameters',
'entry_smtp_host'      		      => 'SMTP Hostname',
'entry_smtp_username'      		  => 'SMTP Username',
'entry_smtp_password'      		  => 'SMTP Password',
'entry_smtp_port'      			  => 'SMTP Port',
'entry_smtp_timeout'      		  => 'SMTP Timeout',

'entry_ssl'=> 'Use SSL',
'entry_robots'=> 'Robots',
'entry_time_zone'                  => 'Time Zone',
'entry_date_format'                => 'Date Format',
'entry_time_format'                => 'Time Format',
'entry_pagination_limit_front'     => 'Pagination Limit (Front)',
'entry_pagination_limit_admin'     => 'Pagination Limit (Admin)',


'entry_seo_url'=> 'Use SEO URLs',
'entry_file_max_size'	          => 'Max File Size',
'entry_file_extensions'            => 'Allowed File Extensions',
'entry_file_mimetypes'             => 'Allowed File Mime Types',
'entry_maintenance_mode'           => 'Maintenance Mode',
'entry_encryption_key'             => 'Encryption Key',
'entry_compression_level'          => 'Output Compression Level',
'entry_display_error'              => 'Display Errors',
'entry_log_error'                  => 'Log Errors',
'entry_error_log_filename'         => 'Error Log Filename',
'entry_status'=> 'Status',

// Help
'help_ssl'                         => 'To use SSL check with your host if a SSL certificate is installed and add the SSL URL to the catalog and admin config files.',
'help_robots'                      => 'A list of web crawler user agents that shared sessions will not be used with. Use separate lines for each user agent.',
'help_seo_url'=> 'To use SEO URLs, apache module mod-rewrite must be installed and you need to rename the htaccess.txt to .htaccess.',
'help_file_max_size'		          => 'The maximum image file size you can upload in Image Manager. Enter as byte.',
'help_file_ext_allowed'            => 'Add which file extensions are allowed to be uploaded. Use a new line for each value.',
'help_file_mime_allowed'           => 'Add which file mime types are allowed to be uploaded. Use a new line for each value.',
'help_maintenance'                 => 'Prevents customers from browsing your store. They will instead see a maintenance message. If logged in as admin, you will see the store as normal.',
'help_password'=> 'Allow forgotten password to be used for the admin. This will be disabled automatically if the system detects a hack attempt.',
'help_encryption'                  => 'Please provide a secret key that will be used to encrypt private information when processing orders.',
'help_compression'                 => 'GZIP for more efficient transfer to requesting clients. Compression level must be between 0 - 9.',
'help_google_analytics'            => 'Login to your <a href="http://www.google.com/analytics/" target="_blank"><u>Google Analytics</u></a> account and after creating your website profile copy and paste the analytics code into this field.',
'help_google_captcha'              => 'Go to <a href="https://www.google.com/recaptcha/intro/index.html" target="_blank"><u>Google reCAPTCHA page</u></a> and register your website.',


// Error
'error_warning'=> 'Warning: Please check the form carefully for errors!',
'error_permission'                 => 'Warning: You do not have permission to modify role!',
'error_name'=> 'Store Name must be between 3 and 32 characters!',
'error_owner'                      => 'Store Owner must be between 3 and 64 characters!',
'error_address'=> 'Store Address must be between 10 and 256 characters!',
'error_email'                      => 'E-Mail Address does not appear to be valid!',
'error_telephone'                  => 'Telephone must be between 3 and 32 characters!',
'error_meta_title'                 => 'Title must be between 3 and 32 characters!',
'error_limit'       	           => 'Limit required!',
'error_login_attempts'       	   => 'Login Attempts must be greater than 0!',
'error_customer_group_display'     => 'You must include the default customer group if you are going to use this feature!',
'error_voucher_min'                => 'Minimum voucher amount required!',
'error_voucher_max'                => 'Maximum voucher amount required!',
'error_processing_status'          => 'You must choose at least 1 order process status',
'error_complete_status'            => 'You must choose at least 1 order complete status',
'error_image_thumb'                => 'Product Image Thumb Size dimensions required!',
'error_image_popup'                => 'Product Image Popup Size dimensions required!',
'error_image_product'              => 'Product List Size dimensions required!',
'error_image_category'             => 'Category List Size dimensions required!',
'error_image_additional'           => 'Additional Product Image Size dimensions required!',
'error_image_related'              => 'Related Product Image Size dimensions required!',
'error_image_compare'              => 'Compare Image Size dimensions required!',
'error_image_wishlist'             => 'Wish List Image Size dimensions required!',
'error_image_cart'                 => 'Cart Image Size dimensions required!',
'error_image_location'             => 'Store Image Size dimensions required!',
'error_ftp_hostname'               => 'FTP Host required!',
'error_ftp_port'                   => 'FTP Port required!',
'error_ftp_username'               => 'FTP Username required!',
'error_ftp_password'               => 'FTP Password required!',
'error_error_filename'             => 'Error Log Filename required!',
'error_encryption'                 => 'Encryption Key must be between 3 and 32 characters!',
];