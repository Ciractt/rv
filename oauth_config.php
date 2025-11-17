<?php
// oauth_config.php - OAuth Provider Configuration
// Add this to your .gitignore to keep credentials secure!

return [
    'google' => [
        'client_id'     => '575632577625-h1051a0omhts8o9dlqiqorn46copa89b.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect_uri'  => SITE_URL . '/oauth_callback.php?provider=google',
        'scopes'        => ['email', 'profile'],
    ],
    
    'twitch' => [
        'client_id'     => 'xn8ffjxmn09rjgyhewerptadmg63bf',
        'client_secret' => '8jmxpnn7mk8jbu8il3j8kpmpa6oevg',
        'redirect_uri'  => SITE_URL . '/oauth_callback.php?provider=twitch',
        'scopes'        => ['user:read:email'],
    ],
    
    'discord' => [
        'client_id'     => '1439613785894813857',
        'client_secret' => 'YDdzZyAXTQLh3ACwodKeQ1NiMXcZXXHu',
        'redirect_uri'  => SITE_URL . '/oauth_callback.php?provider=discord',
        'scopes'        => ['identify', 'email'],
    ],
];