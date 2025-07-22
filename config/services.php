<?php

return [

    // Postmark email service configuration
    // Uses a token from the environment to authenticate with Postmark API
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    // Resend email service configuration
    // Retrieves API key from environment to send transactional emails
    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    // Amazon SES (Simple Email Service) configuration
    // Uses AWS credentials and region from environment to send emails
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // Slack notification configuration
    // Used to send messages to Slack channels using a bot token and default channel
    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // Shopify API configuration
    // Used to interact with a Shopify store using the domain and access token
    'shopify' => [
        'store_domain' => env('SHOPIFY_STORE_DOMAIN'),
        'access_token' => env('SHOPIFY_ACCESS_TOKEN'),
    ],

];
