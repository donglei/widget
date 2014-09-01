from fabric.api import *
from fabric.contrib.project import rsync_project
from fabric.api import task

@task
def debug():
    env.hosts = ['192.168.1.248']
    env.user = 'deploy'
    env.TARGET_DIR = '/srv/deploy/wxmanage'
    env.WWW_DIR = '/srv/deploy/wxmanage/public'

@task
def production():
    env.hosts = ['117.121.26.79']
    env.user = 'ubuntu'
    env.TARGET_DIR = '/srv/deploy/wxmanage'
    env.WWW_DIR = '/srv/deploy/wxmanage/public'

@task
def deploy():
    # Copy files over.
    run('mkdir -p %s' % env.TARGET_DIR)

    rsync_project(remote_dir=env.TARGET_DIR, local_dir='./',
                    delete=True,
                    # extra_opts='--exclude-from=.gitignore',
                    exclude = [
                        'fabfile*',
                        'app/storage/logs/*',
                        'app/storage/sessions/*',
                        'app/storage/views/*',
                        'app/storage/meta/*',
                        'app/storage/cache/*',
                        'public/attached/*',
                        'public/uploads/*',
                        '.DS_Store',
                        '.log',
                        '.git*'
                    ]
    )

    with cd(env.TARGET_DIR):
        run('mkdir -p public/uploads/')
        sudo('''./artisan migrate''')
        sudo('''composer dump -o''')

        # sudo('''sv restart itourism-task_worker''');

@task
def upgrade():
    with cd(env.TARGET_DIR):
        run('composer update laravel/framework');

        # with cd('public'):
            # sudo('''''')
            # run('''rm -rf uploads''')
            # run('''ln -s /data/uploads/ uploads''')
    #     # Update programe configs
    #     with cd('app/config'):
    #         run('''sed -i "s|'url' => '.*'|'url' => '%s'|" app.php''' % (env.SERVICE_HOST))
    #         # run('''sed -i "s|'host' => '.*'|'host' => '%s'|" static.php''' % (env.STATIC_HOST))
    #         # run('''sed -i "s|'password' => 'sasa'|'password' => ''|" database.php''')

    #     #sudo('''composer self-update''')
    #     #sudo('''composer update''')
    #     #sudo('''./artisan migrate:reset''')
    #     #sudo('''./artisan migrate --seed''')

    #     # Update nginx configs
    #     sudo('''sed -i "s|server_name .*;|server_name %s;|" nginx.conf''' % (env.hosts[0]))
    #     # sudo('''sed -i "s|root .*;|root %s;|" nginx.conf''' % (env.WWW_DIR))
    #     sudo('''cp nginx.conf /etc/nginx/site-available/%s.conf''' % (env.hosts[0]))

    #     # Start nginx & php-fpm
    #     sudo('/etc/init.d/nginx start && /etc/init.d/nginx reload')
    #     sudo('/etc/init.d/php-fpm start')
