<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config
set('repository', 'https://github.com/issacgram/depapp.git');

set('git_tty', false);
set('ssh_multiplexing', false);
set('debug', true);

// Environment and shared configuration
add('shared_files', [
    '.env'
]);

add('shared_dirs', [
    'storage',
    'bootstrap/cache'
]);

add('writable_dirs', [
    'bootstrap/cache',
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs'
]);


desc('Push changes and deploy');
task('deploy-changes', function () {
    runLocally('git add .');
    $commitMessage = ask('Enter commit message', 'Update changes');
    runLocally('git commit -m "' . $commitMessage . '"');
    runLocally('git push origin main');
    invoke('deploy');
});

// Hosts
host('89.116.48.146')
    ->set('remote_user', 'deployuser')
    ->set('deploy_path', '/var/www/phpgram.info');

// Deployment tasks
after('deploy:failed', 'deploy:unlock');

// Optional but recommended - add deployment success notification
after('deploy:success', 'artisan:cache:clear');
after('deploy:success', 'artisan:config:cache');
after('deploy:success', 'artisan:route:cache');
after('deploy:success', 'artisan:view:cache');
